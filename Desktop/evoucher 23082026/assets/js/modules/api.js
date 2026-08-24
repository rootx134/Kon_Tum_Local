// modules/api.js — Fetch wrappers with CSRF + error handling

/**
 * Wrapper for fetch() that auto-attaches CSRF token.
 * Use for all POST/PUT/DELETE requests.
 */
export function apiMutate(url, options = {}) {
    const CSRF_TOKEN = window.CSRF_TOKEN;
    const headers = { ...(options.headers || {}) };

    // Always attach via header (works for DELETE + most servers)
    headers['X-CSRF-Token'] = CSRF_TOKEN;

    if (options.body instanceof FormData) {
        // FormData → embed in body (PHP reads $_POST)
        options.body.append('csrf_token', CSRF_TOKEN);

    } else if (typeof options.body === 'string') {
        // JSON body → inject csrf_token into payload
        try {
            const data = JSON.parse(options.body);
            data.csrf_token = CSRF_TOKEN;
            options.body = JSON.stringify(data);
        } catch (e) { /* not JSON — header is enough */ }

    } else if (!options.body) {
        // No body (e.g. POST/DELETE with no payload)
        // → send JSON body so PHP can read csrf_token from JSON input
        headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify({ csrf_token: CSRF_TOKEN });
    }

    options.headers = headers;
    return fetch(url, options);
}
