import { Auth, apiFetch } from './auth.js';

function clearFormErrors(form) {
    form.querySelectorAll('[data-error-for]').forEach((el) => {
        el.textContent = '';
    });
    const box = form.querySelector('[data-form-error]');
    if (box) {
        box.textContent = '';
        box.classList.add('hidden');
    }
}

function applyFieldErrors(form, errors) {
    Object.entries(errors || {}).forEach(([field, messages]) => {
        const el = form.querySelector(`[data-error-for="${field}"]`);
        if (el) el.textContent = messages[0];
    });
}

function showFormError(form, message) {
    const box = form.querySelector('[data-form-error]');
    if (box) {
        box.textContent = message;
        box.classList.remove('hidden');
    }
}

// ---- Nav aware of an existing session (landing + auth pages) ----
document.querySelectorAll('[data-auth-nav]').forEach((nav) => {
    if (Auth.isLoggedIn()) {
        nav.querySelectorAll('[data-guest-only]').forEach((el) => el.classList.add('hidden'));
        nav.querySelectorAll('[data-auth-only]').forEach((el) => el.classList.remove('hidden'));
    }
});

// ---- Login form -> POST /api/login ----
const loginForm = document.querySelector('[data-login-form]');
if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFormErrors(loginForm);
        const submitBtn = loginForm.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        const { ok, status, data, networkError } = await apiFetch('/login', {
            method: 'POST',
            body: JSON.stringify({
                email: loginForm.email.value,
                password: loginForm.password.value,
            }),
        });

        submitBtn.disabled = false;

        if (!ok) {
            if (status === 422) {
                applyFieldErrors(loginForm, data && data.errors);
            } else {
                showFormError(
                    loginForm,
                    networkError
                        ? "Impossible de contacter le serveur. Vérifie ta connexion."
                        : (data && data.message) || 'Identifiants incorrects.',
                );
            }
            return;
        }

        Auth.setSession(data.token, data.user);
        window.location.href = '/app/dashboard';
    });
}

// ---- Register form -> POST /api/register ----
const registerForm = document.querySelector('[data-register-form]');
if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        clearFormErrors(registerForm);
        const submitBtn = registerForm.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        const { ok, status, data, networkError } = await apiFetch('/register', {
            method: 'POST',
            body: JSON.stringify({
                name: registerForm.name.value,
                prenom: registerForm.prenom.value,
                email: registerForm.email.value,
                password: registerForm.password.value,
                password_confirmation: registerForm.password_confirmation.value,
            }),
        });

        submitBtn.disabled = false;

        if (!ok) {
            if (status === 422) {
                applyFieldErrors(registerForm, data && data.errors);
            } else {
                showFormError(
                    registerForm,
                    networkError
                        ? "Impossible de contacter le serveur. Vérifie ta connexion."
                        : (data && data.message) || 'Une erreur est survenue.',
                );
            }
            return;
        }

        Auth.setSession(data.token, data.user);
        window.location.href = '/app/dashboard';
    });
}

// ---- Minimal authenticated dashboard, backed by /api/me + /api/logout ----
const dashRoot = document.querySelector('[data-dashboard-root]');
if (dashRoot) {
    (async () => {
        if (!Auth.isLoggedIn()) {
            window.location.href = '/login';
            return;
        }

        const { ok, data } = await apiFetch('/me');
        if (!ok) {
            Auth.clearSession();
            window.location.href = '/login';
            return;
        }

        const user = data.user;
        dashRoot.querySelector('[data-user-name]').textContent = [user.prenom, user.name].filter(Boolean).join(' ');
        dashRoot.querySelector('[data-user-email]').textContent = user.email;
        dashRoot.querySelector('[data-user-role]').textContent = user.role === 'admin' ? 'Administrateur' : 'Élève';
        dashRoot.classList.remove('hidden');

        const loading = document.querySelector('[data-dashboard-loading]');
        if (loading) loading.classList.add('hidden');
    })();

    const logoutBtn = document.querySelector('[data-logout]');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', async () => {
            logoutBtn.disabled = true;
            await apiFetch('/logout', { method: 'POST' });
            Auth.clearSession();
            window.location.href = '/';
        });
    }
}
