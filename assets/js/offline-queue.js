/* DCK Offline Queue - lets specific forms (Attendance, Fee Collection) keep working
   when internet drops, and auto-syncs to the server once back online. */
(function () {
	var DB_NAME = 'dck_offline_db';
	var DB_VERSION = 1;
	var STORE_NAME = 'pending_sync';
	var db = null;
	var syncing = false;

	function uuid() {
		return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
			var r = (Math.random() * 16) | 0, v = c === 'x' ? r : (r & 0x3) | 0x8;
			return v.toString(16);
		});
	}

	function openDb(callback) {
		if (db) { callback(db); return; }
		var req = indexedDB.open(DB_NAME, DB_VERSION);
		req.onupgradeneeded = function (e) {
			var d = e.target.result;
			if (!d.objectStoreNames.contains(STORE_NAME)) {
				var store = d.createObjectStore(STORE_NAME, { keyPath: 'id', autoIncrement: true });
				store.createIndex('created_at', 'created_at', { unique: false });
			}
		};
		req.onsuccess = function (e) { db = e.target.result; callback(db); };
		req.onerror = function () { console.error('DCK Offline Queue: could not open IndexedDB'); };
	}

	function queueRequest(url, data, label) {
		data.idempotency_key = uuid();
		openDb(function (d) {
			var tx = d.transaction(STORE_NAME, 'readwrite');
			tx.objectStore(STORE_NAME).add({
				url: url, data: data, label: label || 'Action',
				created_at: Date.now(),
			});
			tx.oncomplete = function () {
				updateBadge();
				showToast('Saved offline. Will sync automatically when internet returns.', 'warning');
			};
		});
	}

	function getAllPending(callback) {
		openDb(function (d) {
			var tx = d.transaction(STORE_NAME, 'readonly');
			var req = tx.objectStore(STORE_NAME).getAll();
			req.onsuccess = function () { callback(req.result); };
		});
	}

	function removeRequest(id, callback) {
		openDb(function (d) {
			var tx = d.transaction(STORE_NAME, 'readwrite');
			tx.objectStore(STORE_NAME).delete(id);
			tx.oncomplete = function () { if (callback) callback(); };
		});
	}

	function updateBadge() {
		getAllPending(function (items) {
			var $badge = $('#offline-pending-badge');
			if (items.length > 0) {
				$badge.text(items.length).show();
			} else {
				$badge.hide();
			}
			setBannerState(navigator.onLine ? (items.length ? 'syncing-needed' : 'online') : 'offline', items.length);
		});
	}

	function setBannerState(state, pendingCount) {
		var $banner = $('#dck-offline-banner');
		if (!$banner.length) return;
		if (state === 'offline') {
			$banner.removeClass('dck-banner-success dck-banner-info').addClass('dck-banner-danger').show()
				.html('<i class="fas fa-wifi"></i> You are <strong>offline</strong>. Attendance and Fee Collection will keep working and sync automatically once internet returns.' + (pendingCount ? ' (' + pendingCount + ' pending)' : ''));
		} else if (state === 'syncing-needed') {
			$banner.removeClass('dck-banner-danger dck-banner-success').addClass('dck-banner-info').show()
				.html('<i class="fas fa-sync fa-spin"></i> Back online. Syncing ' + pendingCount + ' pending item(s)...');
		} else {
			if ($banner.hasClass('dck-banner-danger') || $banner.hasClass('dck-banner-info')) {
				$banner.removeClass('dck-banner-danger dck-banner-info').addClass('dck-banner-success')
					.html('<i class="fas fa-check-circle"></i> All changes synced.');
				setTimeout(function () { $banner.fadeOut(); }, 3000);
			} else {
				$banner.hide();
			}
		}
	}

	function showToast(message, type) {
		if (typeof swal === 'undefined') { console.log(message); return; }
		swal({
			toast: true, position: 'top-end', type: type || 'info',
			title: message, buttonsStyling: false, timer: 5000,
		});
	}

	function syncPending() {
		if (syncing || !navigator.onLine) return;
		syncing = true;
		getAllPending(function (items) {
			if (!items.length) { syncing = false; updateBadge(); return; }
			setBannerState('syncing-needed', items.length);

			var next = function (index) {
				if (index >= items.length) {
					syncing = false;
					updateBadge();
					showToast('All offline changes synced successfully.', 'success');
					return;
				}
				var item = items[index];
				$.ajax({
					url: item.url, type: 'POST', data: item.data, dataType: 'json', timeout: 20000,
					success: function () {
						removeRequest(item.id, function () { next(index + 1); });
					},
					error: function () {
						// Network still unreliable or server rejected it - stop, retry on next trigger.
						syncing = false;
						updateBadge();
					},
				});
			};
			next(0);
		});
	}

	// Hook up forms marked offline-capable: <form class="frm-submit-offline" data-offline-label="...">
	$(function () {
		updateBadge();

		$('body').on('submit', 'form.frm-submit-offline', function (e) {
			e.preventDefault();
			var $form = $(this);
			var url = $form.attr('action');
			var data = {};
			$.each($form.serializeArray(), function (i, kv) {
				data[kv.name] = (data[kv.name] !== undefined) ? [].concat(data[kv.name], kv.value) : kv.value;
			});
			var label = $form.data('offline-label') || 'Action';

			if (!navigator.onLine) {
				queueRequest(url, data, label);
				if ($form.data('reset-on-offline-save')) { $form[0].reset(); }
				return;
			}

			// Online: try the real request. If it fails (e.g. connection just dropped), queue it instead.
			var btn = $form.find('[type="submit"]');
			data.idempotency_key = uuid();
			$.ajax({
				url: url, type: 'POST', data: data, dataType: 'json', timeout: 8000,
				beforeSend: function () { btn.button('loading'); },
				success: function (resp) {
					btn.button('reset');
					if (resp.status === 'fail') {
						$('.error').html('');
						$.each(resp.error || {}, function (k, v) {
							$form.find("[name='" + k + "']").parents('.form-group').find('.error').html(v);
						});
						return;
					}
					showToast(resp.message || 'Saved successfully.', 'success');
					if ($form.data('reload-on-success')) {
						setTimeout(function () { location.reload(true); }, 800);
					}
				},
				error: function () {
					btn.button('reset');
					queueRequest(url, data, label);
				},
			});
		});

		window.addEventListener('online', function () {
			showToast('Connection restored. Syncing offline changes...', 'info');
			syncPending();
		});
		window.addEventListener('offline', function () {
			updateBadge();
		});

		// Backup poll - some browsers/networks don't fire online/offline reliably.
		setInterval(function () {
			updateBadge();
			if (navigator.onLine) { syncPending(); }
		}, 20000);
	});

	window.dckOfflineQueue = { queueRequest: queueRequest, syncPending: syncPending, getAllPending: getAllPending };
})();
