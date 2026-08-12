@extends('layouts.app')

@section('title', 'Quiz — MathGenius')

@section('content')
    <div data-page="quiz-setup" class="max-w-[820px] animate-[mgFade_.4s_ease_both]">
        <div data-setup-view>
            <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">QUIZ</div>
            <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Faire un quiz</h1>
            <p class="mt-2 max-w-lg text-[15px] text-[#5C667E]">Choisis une leçon : l'IA génère un quiz sur mesure à partir de son contenu.</p>

            <div class="mt-7 rounded-[18px] border border-[#E4E7EF] bg-white p-[26px] shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono mb-3.5 text-[10.5px] tracking-[.1em] text-[#77819A]">1 · CHOISIR UN CHAPITRE</div>
                <div data-setup-loading class="py-6 text-center text-[13px] text-[#77819A]">Chargement des chapitres…</div>
                <div data-setup-grid class="hidden grid grid-cols-1 gap-2.5 sm:grid-cols-2"></div>

                <div data-lecon-block class="hidden mt-7 border-t border-[#E4E7EF] pt-6">
                    <div class="mg-mono mb-3.5 text-[10.5px] tracking-[.1em] text-[#77819A]">2 · CHOISIR UNE LEÇON</div>
                    <div data-lecon-grid class="grid grid-cols-1 gap-2.5"></div>
                </div>

                <div data-options-block class="hidden mt-7 border-t border-[#E4E7EF] pt-6">
                    <div class="mg-mono mb-3.5 text-[10.5px] tracking-[.1em] text-[#77819A]">3 · RÉGLAGES</div>
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="mb-2 block text-[13px] text-[#5C667E]">Difficulté</label>
                            <div data-difficulte-group class="flex gap-2">
                                @foreach (['facile' => 'Facile', 'moyen' => 'Moyen', 'difficile' => 'Difficile'] as $value => $label)
                                    <button type="button" data-difficulte="{{ $value }}"
                                            class="flex-1 rounded-[10px] border px-3 py-2.5 text-[13px] transition {{ $value === 'moyen' ? 'border-[#5E7F12] bg-[#F3F8E3] text-[#12161F]' : 'border-[#E4E7EF] bg-white text-[#5C667E] hover:border-[#C9CFDD]' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div>
                            <label class="mb-2 block text-[13px] text-[#5C667E]">Nombre de questions</label>
                            <div data-nombre-group class="flex gap-2">
                                @foreach ([5, 10, 20] as $value)
                                    <button type="button" data-nombre="{{ $value }}"
                                            class="flex-1 rounded-[10px] border px-3 py-2.5 text-[13px] transition {{ $value === 5 ? 'border-[#5E7F12] bg-[#F3F8E3] text-[#12161F]' : 'border-[#E4E7EF] bg-white text-[#5C667E] hover:border-[#C9CFDD]' }}">
                                        {{ $value }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-6 flex items-center justify-between border-t border-[#E4E7EF] pt-5">
                    <div data-setup-hint class="text-[12.5px] text-[#77819A]">Sélectionne un chapitre pour continuer.</div>
                    <button type="button" data-setup-submit disabled class="rounded-[11px] bg-[#C6F24E] px-6 py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:cursor-not-allowed disabled:opacity-50">
                        Générer le quiz
                    </button>
                </div>

                <p data-setup-error class="mt-4 hidden rounded-[10px] border border-[#F3D8CE] bg-[#FDF2ED] px-3.5 py-2.5 text-[13px] text-[#C4442A]"></p>
            </div>
        </div>

        <div data-generating-view class="hidden grid min-h-[60vh] place-items-center">
            <div class="max-w-[420px] text-center">
                <div class="relative mx-auto mb-8 h-[150px] w-[150px]">
                    <div class="mg-spin absolute inset-0 rounded-full border border-[#DDE1EB]">
                        <span class="absolute -top-1 left-1/2 h-2 w-2 rounded-full bg-[#C6F24E]" style="box-shadow:0 0 16px #C6F24E;"></span>
                    </div>
                    <div class="absolute inset-6 grid place-items-center rounded-full border border-dashed border-[#DDE1EB]">
                        <span class="mg-mono text-[26px] text-[#7C5CFF]">✦</span>
                    </div>
                </div>
                <div class="text-[21px] font-semibold tracking-tight text-[#12161F]">L'IA construit ton quiz…</div>
                <div data-generating-step class="mg-mono mt-2.5 h-[18px] text-[12px] text-[#7C5CFF]">Lecture de la leçon…</div>
                <div class="mt-6 h-1 overflow-hidden rounded-full bg-[#E4E7EF]">
                    <div data-generating-bar class="h-full rounded-full bg-gradient-to-r from-[#C6F24E] to-[#38E1D4] transition-[width]" style="width:0%"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="quiz-setup"]');
    if (!root) return;
    const { apiFetch, escapeHtml } = window.MG;

    const POLL_INTERVAL_MS = 2000;
    const POLL_TIMEOUT_MS = 120000;

    let chapitres = [];
    let lecons = [];
    let selectedChapitre = new URLSearchParams(window.location.search).get('chapitre');
    let selectedLecon = null;
    let difficulte = 'moyen';
    let nombreQuestions = 5;

    const el = (sel) => root.querySelector(sel);

    document.addEventListener('mg:user-ready', async () => {
        const { ok, data } = await apiFetch('/chapitres');
        chapitres = ok ? (data.data || []) : [];

        el('[data-setup-loading]').classList.add('hidden');
        el('[data-setup-grid]').classList.remove('hidden');

        if (!chapitres.length) {
            el('[data-setup-grid]').innerHTML = '<div class="col-span-2 py-6 text-center text-[13px] text-[#77819A]">Aucun chapitre disponible.</div>';
            return;
        }

        paintChapitres();
        if (selectedChapitre) loadLecons();
    }, { once: true });

    function paintChapitres() {
        const grid = el('[data-setup-grid]');
        grid.innerHTML = chapitres.map((c) => {
            const active = String(c.id) === String(selectedChapitre);
            return `
                <button type="button" data-chapitre-option="${c.id}" class="flex items-center justify-between gap-2.5 rounded-[13px] border p-4 text-left transition ${active ? 'border-[#5E7F12] bg-[#F3F8E3]' : 'border-[#E4E7EF] bg-white hover:border-[#C9CFDD]'}">
                    <span>
                        <span class="block text-[14.5px] font-medium text-[#12161F]">${escapeHtml(c.titre)}</span>
                        <span class="mg-mono mt-1 block text-[10px] text-[#77819A]">${c.niveau ? escapeHtml(c.niveau.nom) : ''}</span>
                    </span>
                    <span class="text-[15px]" style="color:${active ? '#5E7F12' : 'transparent'}">✓</span>
                </button>
            `;
        }).join('');

        grid.querySelectorAll('[data-chapitre-option]').forEach((btn) => {
            btn.addEventListener('click', () => {
                selectedChapitre = btn.dataset.chapitreOption;
                selectedLecon = null;
                paintChapitres();
                loadLecons();
            });
        });
    }

    async function loadLecons() {
        const block = el('[data-lecon-block]');
        const grid = el('[data-lecon-grid]');
        block.classList.remove('hidden');
        grid.innerHTML = '<div class="py-4 text-center text-[13px] text-[#77819A]">Chargement des leçons…</div>';
        refreshSubmit();

        const { ok, data } = await apiFetch(`/chapitres/${selectedChapitre}/lecons`);
        lecons = ok ? (data.data || []) : [];

        if (!lecons.length) {
            grid.innerHTML = '<div class="py-4 text-center text-[13px] text-[#77819A]">Aucune leçon dans ce chapitre.</div>';
            return;
        }

        paintLecons();
    }

    function paintLecons() {
        const grid = el('[data-lecon-grid]');
        grid.innerHTML = lecons.map((l) => {
            const active = String(l.id) === String(selectedLecon);
            return `
                <button type="button" data-lecon-option="${l.id}" class="flex items-center justify-between gap-2.5 rounded-[13px] border p-4 text-left transition ${active ? 'border-[#5E7F12] bg-[#F3F8E3]' : 'border-[#E4E7EF] bg-white hover:border-[#C9CFDD]'}">
                    <span class="text-[14.5px] font-medium text-[#12161F]">${escapeHtml(l.titre)}</span>
                    <span class="text-[15px]" style="color:${active ? '#5E7F12' : 'transparent'}">✓</span>
                </button>
            `;
        }).join('');

        grid.querySelectorAll('[data-lecon-option]').forEach((btn) => {
            btn.addEventListener('click', () => {
                selectedLecon = btn.dataset.leconOption;
                paintLecons();
                el('[data-options-block]').classList.remove('hidden');
                refreshSubmit();
            });
        });
    }

    function wireChoiceGroup(groupSelector, attribute, onPick) {
        el(groupSelector).querySelectorAll(`[data-${attribute}]`).forEach((btn) => {
            btn.addEventListener('click', () => {
                el(groupSelector).querySelectorAll(`[data-${attribute}]`).forEach((other) => {
                    other.className = other.className
                        .replace('border-[#5E7F12] bg-[#F3F8E3] text-[#12161F]', 'border-[#E4E7EF] bg-white text-[#5C667E] hover:border-[#C9CFDD]');
                });
                btn.className = btn.className
                    .replace('border-[#E4E7EF] bg-white text-[#5C667E] hover:border-[#C9CFDD]', 'border-[#5E7F12] bg-[#F3F8E3] text-[#12161F]');
                onPick(btn.dataset[attribute]);
            });
        });
    }

    wireChoiceGroup('[data-difficulte-group]', 'difficulte', (v) => { difficulte = v; });
    wireChoiceGroup('[data-nombre-group]', 'nombre', (v) => { nombreQuestions = Number(v); });

    function refreshSubmit() {
        const ready = !!selectedLecon;
        el('[data-setup-submit]').disabled = !ready;
        el('[data-setup-hint]').textContent = ready
            ? 'Prêt à générer le quiz.'
            : (selectedChapitre ? 'Sélectionne une leçon pour continuer.' : 'Sélectionne un chapitre pour continuer.');
    }

    function showError(message) {
        el('[data-generating-view]').classList.add('hidden');
        el('[data-setup-view]').classList.remove('hidden');
        const err = el('[data-setup-error]');
        err.textContent = message;
        err.classList.remove('hidden');
    }

    const steps = [
        'Lecture de la leçon…',
        'Rédaction des questions…',
        'Vérification des réponses…',
        'Derniers ajustements…',
    ];

    // Le job de génération est asynchrone : on interroge le quiz jusqu'à
    // ce que ses questions apparaissent.
    async function pollUntilReady(quizId) {
        const deadline = Date.now() + POLL_TIMEOUT_MS;

        while (Date.now() < deadline) {
            await new Promise((r) => setTimeout(r, POLL_INTERVAL_MS));

            const { ok, data } = await apiFetch(`/quiz/${quizId}`);
            if (ok && data && data.data && (data.data.questions || []).length) {
                return true;
            }
        }

        return false;
    }

    el('[data-setup-submit]').addEventListener('click', async () => {
        if (!selectedLecon) return;

        el('[data-setup-error]').classList.add('hidden');
        el('[data-setup-view]').classList.add('hidden');
        el('[data-generating-view]').classList.remove('hidden');

        const stepEl = el('[data-generating-step]');
        const barEl = el('[data-generating-bar]');
        let i = 0;
        const timer = setInterval(() => {
            i = Math.min(i + 1, steps.length - 1);
            stepEl.textContent = steps[i];
            barEl.style.width = `${Math.round(((i + 1) / steps.length) * 90)}%`;
        }, 3000);

        const { ok, status, data } = await apiFetch('/quiz/generate', {
            method: 'POST',
            body: JSON.stringify({
                id_lecon: Number(selectedLecon),
                difficulte: difficulte,
                nombre_questions: nombreQuestions,
            }),
        });

        if (!ok) {
            clearInterval(timer);
            showError(status === 422
                ? 'Cette leçon ne permet pas de générer un quiz.'
                : ((data && data.message) || "La génération a échoué. Réessaie dans un instant."));
            return;
        }

        const quizId = data.data.id;
        const ready = await pollUntilReady(quizId);
        clearInterval(timer);

        if (!ready) {
            showError("La génération prend plus de temps que prévu. Réessaie dans quelques instants.");
            return;
        }

        barEl.style.width = '100%';
        window.location.href = `/app/quiz/${quizId}/jouer`;
    });
});
</script>
@endpush
