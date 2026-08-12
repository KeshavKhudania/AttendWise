const CACHE_NAME = 'attendwise-student-v2';
const STATIC_ASSETS = [
  'student/login',
  'manifest.json',
  'assets/images/logo.png'
];

// Install Event - Pre-cache core shell safely
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => {
      console.log('[PWA SW] Pre-caching static assets');
      return Promise.allSettled(
        STATIC_ASSETS.map((asset) => cache.add(asset).catch(err => console.warn('[PWA SW] Optional asset cache failed:', asset, err)))
      );
    }).then(() => self.skipWaiting())
  );
});

// Activate Event - Clean old caches
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cache) => {
          if (cache !== CACHE_NAME) {
            console.log('[PWA SW] Clearing old cache:', cache);
            return caches.delete(cache);
          }
        })
      );
    }).then(() => self.clients.claim())
  );
});

// Fetch Event - Network First with Cache Fallback
self.addEventListener('fetch', (event) => {
  // Only intercept GET requests for HTTP/HTTPS
  if (event.request.method !== 'GET' || !event.request.url.startsWith('http')) {
    return;
  }

  // Handle navigation & page requests
  if (event.request.mode === 'navigate') {
    event.respondWith(
      fetch(event.request)
        .then((response) => {
          if (response && response.status === 200) {
            const copy = response.clone();
            caches.open(CACHE_NAME).then((cache) => cache.put(event.request, copy));
          }
          return response;
        })
        .catch(() => {
          return caches.match(event.request).then((cachedResponse) => {
            if (cachedResponse) {
              return cachedResponse;
            }
            return caches.match('student/login');
          });
        })
    );
    return;
  }

  // Stale-While-Revalidate for images, scripts & styles
  event.respondWith(
    caches.match(event.request).then((cachedResponse) => {
      const fetchPromise = fetch(event.request).then((networkResponse) => {
        if (networkResponse && networkResponse.status === 200) {
          const responseToCache = networkResponse.clone();
          caches.open(CACHE_NAME).then((cache) => cache.put(event.request, responseToCache));
        }
        return networkResponse;
      }).catch(() => {
        // Suppress network errors for offline assets
      });

      return cachedResponse || fetchPromise;
    })
  );
});
