// Runtime partagé de l'espace authentifié (/app/*).
// Expose window.MG puis diffuse `mg:user-ready` une fois l'utilisateur chargé.

import { Auth, apiFetch } from './auth.js';

function escapeHtml(value) {
    if (value === null || value === undefined) return '';
    return String(value)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function fmtDate(value) {
    if (!value) return '—';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) return '—';

    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: 'short',
        year: 'numeric',
    });
}

function fmtDuration(totalSeconds) {
    const seconds = Math.max(0, Math.floor(Number(totalSeconds) || 0));
    const minutes = Math.floor(seconds / 60);
    const rest = seconds % 60;

    return `${String(minutes).padStart(2, '0')}:${String(rest).padStart(2, '0')}`;
}

function fullNameOf(user) {
    if (!user) return '—';
    const full = [user.prenom, user.name].filter(Boolean).join(' ').trim();

    return full || user.email || '—';
}

function initialsOf(user) {
    if (!user) return '··';
    const letters = [user.prenom, user.name]
        .filter(Boolean)
        .map((part) => part.trim().charAt(0))
        .join('');

    return (letters || (user.email || '·').charAt(0)).toUpperCase() || '··';
}

let toastHandle = null;

function showToast(message) {
    let toast = document.querySelector('[data-mg-toast]');
    if (!toast) {
        toast = document.createElement('div');
        toast.setAttribute('data-mg-toast', '');
        toast.className =
            'fixed bottom-6 left-1/2 z-[100] -translate-x-1/2 rounded-[11px] border border-[#E4E7EF] bg-white px-5 py-3 text-[13.5px] text-[#12161F] shadow-[0_10px_40px_-12px_rgba(18,22,31,.35)] transition-opacity duration-200';
        document.body.appendChild(toast);
    }

    toast.textContent = message;
    toast.style.opacity = '1';

    clearTimeout(toastHandle);
    toastHandle = setTimeout(() => {
        toast.style.opacity = '0';
    }, 2800);
}

window.MG = {
    Auth,
    apiFetch,
    escapeHtml,
    fmtDate,
    fmtDuration,
    fullNameOf,
    initialsOf,
    showToast,
};

// ---- Amorçage des pages protégées ----
function paintIdentity(user) {
    document.querySelectorAll('[data-user-name]').forEach((el) => {
        el.textContent = fullNameOf(user);
    });
    document.querySelectorAll('[data-user-initials]').forEach((el) => {
        el.textContent = initialsOf(user);
    });
    document.querySelectorAll('[data-user-level]').forEach((el) => {
        el.textContent = user.niveau ? user.niveau.nom : 'Niveau non défini';
    });

    if (user.role === 'formateur' || user.role === 'admin') {
        document.querySelectorAll('[data-formateur-nav]').forEach((el) => {
            el.classList.remove('hidden');
            el.classList.add('flex');
        });
    }
}

function wireLogout() {
    document.querySelectorAll('[data-logout]').forEach((btn) => {
        btn.addEventListener('click', async () => {
            btn.disabled = true;
            await apiFetch('/logout', { method: 'POST' });
            Auth.clearSession();
            window.location.href = '/';
        });
    });
}

async function bootProtectedPage() {
    if (!document.body.hasAttribute('data-requires-auth')) return;

    if (!Auth.isLoggedIn()) {
        window.location.href = '/login';
        return;
    }

    const { ok, data } = await apiFetch('/me');
    if (!ok || !data || !data.user) {
        Auth.clearSession();
        window.location.href = '/login';
        return;
    }

    const user = data.user;
    Auth.setSession(Auth.getToken(), user);

    paintIdentity(user);
    wireLogout();

    document.dispatchEvent(new CustomEvent('mg:user-ready', { detail: { user } }));
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bootProtectedPage, { once: true });
} else {
    bootProtectedPage();
}
