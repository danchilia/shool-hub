/* DCK Offline Service Worker
   Scope: lets Attendance and Fee Collection pages re-open from cache when there is
   no internet (e.g. browser/tab was closed and reopened while offline). Actual form
   submissions are handled separately by assets/js/offline-queue.js (IndexedDB queue). */
var CACHE_VERSION = 'dck-offline-v1';
var OFFLINE_CAPABLE_PATHS = ['attendance/student_entry', 'attendance/employees_entry', 'fees/collect'];

var CORE_ASSETS = [
	'assets/js/offline-queue.js',
	'assets/js/app.fn.js',
	'assets/js/app.js',
	'assets/js/custom.js',
];

self.addEventListener('install', function (event) {
	self.skipWaiting();
	event.waitUntil(
		caches.open(CACHE_VERSION).then(function (cache) {
			return Promise.all(
				CORE_ASSETS.map(function (url) {
					return cache.add(url).catch(function () { /* ignore missing/optional asset */ });
				})
			);
		})
	);
});

self.addEventListener('activate', function (event) {
	event.waitUntil(
		caches.keys().then(function (keys) {
			return Promise.all(
				keys.filter(function (key) { return key !== CACHE_VERSION; })
					.map(function (key) { return caches.delete(key); })
			);
		})
	);
	self.clients.claim();
});

function isOfflineCapablePage(url) {
	for (var i = 0; i < OFFLINE_CAPABLE_PATHS.length; i++) {
		if (url.indexOf(OFFLINE_CAPABLE_PATHS[i]) !== -1) return true;
	}
	return false;
}

self.addEventListener('fetch', function (event) {
	var request = event.request;

	// Never intercept POST/PUT/DELETE - those are real data writes handled by offline-queue.js.
	if (request.method !== 'GET') return;

	var url = request.url;

	// Page navigations to the offline-capable screens: try network first, fall back to
	// the last cached copy of that exact page if there's no connection.
	if (request.mode === 'navigate' && isOfflineCapablePage(url)) {
		event.respondWith(
			fetch(request)
				.then(function (response) {
					var copy = response.clone();
					caches.open(CACHE_VERSION).then(function (cache) { cache.put(request, copy); });
					return response;
				})
				.catch(function () {
					return caches.match(request).then(function (cached) {
						return cached || new Response(
							'<h2>You are offline</h2><p>This page has not been opened before, so it cannot load without internet. Open it once while online first.</p>',
							{ headers: { 'Content-Type': 'text/html' } }
						);
					});
				})
		);
		return;
	}

	// Static assets (css/js/images): cache-first for speed and offline availability.
	if (/\.(js|css|png|jpg|jpeg|gif|svg|woff2?)$/.test(url)) {
		event.respondWith(
			caches.match(request).then(function (cached) {
				return cached || fetch(request).then(function (response) {
					var copy = response.clone();
					caches.open(CACHE_VERSION).then(function (cache) { cache.put(request, copy); });
					return response;
				}).catch(function () { return cached; });
			})
		);
	}
});
