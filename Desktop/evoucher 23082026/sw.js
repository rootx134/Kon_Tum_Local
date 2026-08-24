const CACHE_NAME = 'ktp-admin-v3';
const ASSETS_TO_CACHE = [
  '/assets/css/admin.css',
  '/assets/js/admin.js',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css',
  'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap'
];

self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      return Promise.allSettled(
        ASSETS_TO_CACHE.map(url => cache.add(url).catch(e => console.warn('Cache failed for', url, e)))
      );
    })
  );
  self.skipWaiting();
});

self.addEventListener('fetch', (event) => {
  if (event.request.method !== 'GET') return;

  // Không cache PHP files và JS modules (luôn fetch mới)
  const url = new URL(event.request.url);
  if (!url.protocol.startsWith('http')) return;
  if (url.pathname.endsWith('.php') || url.pathname === '/') return;
  if (url.pathname.includes('/assets/js/modules/')) return;

  event.respondWith(
    caches.match(event.request).then((response) => {
      if (response) {
        fetch(event.request.clone()).then((networkResponse) => {
          if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') return;
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, networkResponse.clone()));
        }).catch(() => {});
        return response;
      }

      return fetch(event.request).then((networkResponse) => {
        if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
          return networkResponse;
        }
        let responseToCache = networkResponse.clone();
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseToCache));
        return networkResponse;
      }).catch(() => {
        return new Response('Offline mode or Network error.', {
          status: 503,
          statusText: 'Service Unavailable',
          headers: new Headers({ 'Content-Type': 'text/plain' })
        });
      });
    })
  );
});

self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.filter(name => name !== CACHE_NAME).map(name => caches.delete(name))
      );
    })
  );
  self.clients.claim();
});
