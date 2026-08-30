// DCK School Management PWA Service Worker
const CACHE_NAME = 'dck-schools-v2';

const STATIC_ASSETS = [
    '/assets/images/favicon.png',
];

self.addEventListener('install', function(event) {
    event.waitUntil(
        caches.open(CACHE_NAME).then(function(cache) {
            return Promise.all(
                STATIC_ASSETS.map(function(url) {
                    return cache.add(url).catch(function() { /* ignore if missing */ });
                })
            );
        }).then(function() {
            return self.skipWaiting();
        })
    );
});

self.addEventListener('activate', function(event) {
    event.waitUntil(
        caches.keys().then(function(names) {
            return Promise.all(
                names.filter(function(n) { return n !== CACHE_NAME; })
                     .map(function(n) { return caches.delete(n); })
            );
        }).then(function() {
            return self.clients.claim();
        })
    );
});

self.addEventListener('fetch', function(event) {
    if (event.request.method !== 'GET') return;

    event.respondWith(
        fetch(event.request).catch(function() {
            return caches.match(event.request).then(function(cached) {
                return cached || new Response(
                    '<html><body style="font-family:sans-serif;text-align:center;padding:40px"><h2>You are offline</h2><p>Please check your internet connection.</p></body></html>',
                    { headers: { 'Content-Type': 'text/html' } }
                );
            });
        })
    );
});

self.addEventListener('push', function(event) {
    var data = event.data ? event.data.json() : { title: 'CST SchoolHub', body: 'New notification' };
    event.waitUntil(
        self.registration.showNotification(data.title || 'CST SchoolHub', {
            body: data.body || '',
            icon: '/assets/images/favicon.png',
            badge: '/assets/images/favicon.png',
            data: { url: data.url || '/dashboard' }
        })
    );
});

self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    var url = event.notification.data && event.notification.data.url
        ? event.notification.data.url
        : '/dashboard';
    event.waitUntil(clients.openWindow(url));
});
