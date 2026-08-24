<?php
// sw.php — Dynamic Service Worker with DB-managed cache version
// Served as application/javascript so the browser treats it as a SW script.

require_once __DIR__ . '/config.php';

header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Pragma: no-cache');

// Read current SW cache version from settings table
$version = 1;
try {
    $stmt = $pdo->prepare("SELECT `value` FROM settings WHERE `key` = 'sw_cache_version'");
    $stmt->execute();
    $row = $stmt->fetch();
    if ($row) $version = (int) $row['value'];
} catch (Exception $e) {
    // Table not ready yet, use default
}
?>
const CACHE_NAME = 'ktp-admin-v<?= $version ?>';
const ASSETS_TO_CACHE = [
  '/assets/css/admin.css',
  '/assets/js/admin.js',
  '/assets/vendor/fontawesome/css/all.min.css',
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

  const url = new URL(event.request.url);
  // Never cache PHP files, root, or JS modules (always fetch fresh)
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
        const toCache = networkResponse.clone(); // clone BEFORE body is consumed
        caches.open(CACHE_NAME).then((cache) => cache.put(event.request, toCache));
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
