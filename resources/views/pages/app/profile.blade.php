@extends('layouts.app')

@section('title', 'Profil — MathGenius')

@section('content')
    <div data-page="profile" class="max-w-[680px] animate-[mgFade_.4s_ease_both]">
        <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">MON COMPTE</div>
        <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Profil</h1>

        <form data-profile-form class="mt-6 rounded-[20px] border border-[#E4E7EF] bg-white p-[28px] shadow-[0_1px_2px_rgba(18,22,31,.05)]" novalidate>
            <div class="flex items-center gap-[18px] border-b border-[#E4E7EF] pb-6">
                <span data-profile-initials class="grid h-[70px] w-[70px] shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#7C5CFF] to-[#38E1D4] text-[24px] font-semibold text-[#12161F]">··</span>
                <div class="min-w-0">
                    <div data-profile-name class="truncate text-[20px] font-semibold text-[#12161F]">—</div>
                    <div data-profile-meta class="mt-1.5 text-[13.5px] text-[#5C667E]">—</div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-3.5 pt-6 sm:grid-cols-2">
                <div>
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">PRÉNOM</label>
                    <input name="prenom" type="text" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                </div>
                <div>
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">NOM</label>
                    <input name="name" type="text" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                </div>
                <div class="sm:col-span-2">
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">EMAIL</label>
                    <input name="email" type="email" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                    <p data-error-for="email" class="mt-1 text-xs text-[#C4442A]"></p>
                </div>
                <div>
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">NOUVEAU MOT DE PASSE</label>
                    <input name="password" type="password" placeholder="Laisser vide pour ne pas changer" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                    <p data-error-for="password" class="mt-1 text-xs text-[#C4442A]"></p>
                </div>
                <div>
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">CONFIRMER LE MOT DE PASSE</label>
                    <input name="password_confirmation" type="password" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                </div>
                <div class="sm:col-span-2">
                    <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">NIVEAU SCOLAIRE</label>
                    <div data-profile-levels class="flex flex-wrap gap-2"></div>
                    <input type="hidden" name="niveau_id">
                </div>
            </div>

            <p data-form-error class="mt-4 hidden rounded-[10px] border border-[#F3D8CE] bg-[#FDF2ED] px-3.5 py-2.5 text-[13px] text-[#C4442A]"></p>

            <div class="mt-6 flex items-center gap-4">
                <button type="submit" class="rounded-[11px] bg-[#C6F24E] px-6 py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:opacity-60">Enregistrer</button>
                <span data-profile-saved class="text-[13.5px] text-[#5E7F12]"></span>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="profile"]');
    if (!root) return;
    const { apiFetch, escapeHtml, initialsOf, fullNameOf } = window.MG;
    const form = root.querySelector('[data-profile-form]');
    let niveaux = [];
    let currentNiveauId = null;

    document.addEventListener('mg:user-ready', async (e) => {
        const user = e.detail.user;
        fillForm(user);

        const { ok, data } = await apiFetch('/niveaux');
        niveaux = ok ? (data.data || []) : [];
        currentNiveauId = user.niveau ? user.niveau.id : null;
        paintLevels();
    }, { once: true });

    function fillForm(user) {
        form.prenom.value = user.prenom || '';
        form.name.value = user.name || '';
        form.email.value = user.email || '';
        root.querySelector('[data-profile-initials]').textContent = initialsOf(user);
        root.querySelector('[data-profile-name]').textContent = fullNameOf(user);
        root.querySelector('[data-profile-meta]').textContent = `${user.email} · ${{ admin: 'Administrateur', formateur: 'Formateur' }[user.role] || 'Élève'}`;
    }

    function paintLevels() {
        const wrap = root.querySelector('[data-profile-levels]');
        wrap.innerHTML = niveaux.map((n) => {
            const active = String(n.id) === String(currentNiveauId);
            return `<button type="button" data-level="${n.id}" class="rounded-[10px] border px-3.5 py-2.5 text-[12.5px] transition ${active ? 'border-[#5E7F12] bg-[#F3F8E3] text-[#5E7F12]' : 'border-[#DEE2EC] bg-[#F4F6FA] text-[#5C667E]'}">${escapeHtml(n.nom)}</button>`;
        }).join('');
        form.niveau_id.value = currentNiveauId || '';

        wrap.querySelectorAll('[data-level]').forEach((btn) => {
            btn.addEventListener('click', () => {
                currentNiveauId = btn.dataset.level;
                paintLevels();
            });
        });
    }

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        form.querySelectorAll('[data-error-for]').forEach((el) => { el.textContent = ''; });
        const errBox = form.querySelector('[data-form-error]');
        errBox.classList.add('hidden');
        root.querySelector('[data-profile-saved]').textContent = '';

        const payload = {
            prenom: form.prenom.value,
            name: form.name.value,
            email: form.email.value,
            niveau_id: currentNiveauId || null,
        };
        if (form.password.value) {
            payload.password = form.password.value;
            payload.password_confirmation = form.password_confirmation.value;
        }

        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;

        const { ok, status, data } = await apiFetch('/me', { method: 'PUT', body: JSON.stringify(payload) });
        submitBtn.disabled = false;

        if (!ok) {
            if (status === 422 && data && data.errors) {
                Object.entries(data.errors).forEach(([field, messages]) => {
                    const el = form.querySelector(`[data-error-for="${field}"]`);
                    if (el) el.textContent = messages[0];
                });
            } else {
                errBox.textContent = (data && data.message) || "Impossible d'enregistrer le profil.";
                errBox.classList.remove('hidden');
            }
            return;
        }

        window.MG.Auth.setSession(window.MG.Auth.getToken(), data.user);
        fillForm(data.user);
        form.password.value = '';
        form.password_confirmation.value = '';
        root.querySelector('[data-profile-saved]').textContent = '✓ Profil mis à jour';
        document.querySelectorAll('[data-user-name]').forEach((el) => { el.textContent = fullNameOf(data.user); });
        document.querySelectorAll('[data-user-initials]').forEach((el) => { el.textContent = initialsOf(data.user); });
    });
});
</script>
@endpush
