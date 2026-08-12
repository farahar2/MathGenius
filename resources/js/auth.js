// Session et accès API partagés par le site vitrine (site.js) et
// l'espace authentifié (app.js). Ce module était dupliqué à l'identique
// dans les deux fichiers, tous deux chargés sur chaque page.

const TOKEN_KEY = 'mg_token';
const USER_KEY = 'mg_user';

export const Auth = {
    getToken: () => localStorage.getItem(TOKEN_KEY),
    getUser() {
        try {
            return JSON.parse(localStorage.getItem(USER_KEY) || 'null');
        } catch (e) {
            return null;
        }
    },
    setSession(token, user) {
        localStorage.setItem(TOKEN_KEY, token);
        localStorage.setItem(USER_KEY, JSON.stringify(user));
    },
    clearSession() {
        localStorage.removeItem(TOKEN_KEY);
        localStorage.removeItem(USER_KEY);
    },
    isLoggedIn() {
        return !!Auth.getToken();
    },
};

export async function apiFetch(path, options = {}) {
    const token = Auth.getToken();
    const headers = Object.assign(
        { Accept: 'application/json', 'Content-Type': 'application/json' },
        options.headers || {},
        token ? { Authorization: `Bearer ${token}` } : {},
    );

    let res;
    let data = null;
    try {
        res = await fetch(`/api${path}`, Object.assign({}, options, { headers }));
        data = await res.json().catch(() => null);
    } catch (e) {
        return { ok: false, status: 0, data: null, networkError: true };
    }

    return { ok: res.ok, status: res.status, data };
}
