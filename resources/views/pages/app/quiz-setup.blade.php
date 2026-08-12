@extends('layouts.app')

@section('title', 'Quiz — MathGenius')

@section('content')
    <div data-page="quiz-setup" class="max-w-[820px] animate-[mgFade_.4s_ease_both]">
        <div data-setup-view>
            <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">QUIZ</div>
            <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Faire un quiz</h1>
            <p class="mt-2 max-w-lg text-[15px] text-[#5C667E]">Choisis un chapitre : le quiz correspondant, avec ses questions, s'ouvrira directement.</p>

            <div class="mt-7 rounded-[18px] border border-[#E4E7EF] bg-white p-[26px] shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono mb-3.5 text-[10.5px] tracking-[.1em] text-[#77819A]">CHOISIR UN CHAPITRE</div>
                <div data-setup-loading class="py-6 text-center text-[13px] text-[#77819A]">Chargement des chapitres…</div>
                <div data-setup-grid class="hidden grid grid-cols-1 gap-2.5 sm:grid-cols-2"></div>

                <div class="mt-6 flex items-center justify-between border-t border-[#E4E7EF] pt-5">
                    <div data-setup-hint class="text-[12.5px] text-[#77819A]">Sélectionne un chapitre pour continuer.</div>
                    <button type="button" data-setup-submit disabled class="rounded-[11px] bg-[#C6F24E] px-6 py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:cursor-not-allowed disabled:opacity-50">
                        Commencer le quiz
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
                <div data-generating-step class="mg-mono mt-2.5 h-[18px] text-[12px] text-[#7C5CFF]">Lecture du chapitre…</div>
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

    let chapitres = [];
    let selected = new URLSearchParams(window.location.search).get('chapitre');

    document.addEventListener('mg:user-ready', async () => {
        const { ok, data } = await apiFetch('/chapitres');
        chapitres = ok ? (data.data || []) : [];
        root.querySelector('[data-setup-loading]').classList.add('hidden');
        const grid = root.querySelector('[data-setup-grid]');
        grid.classList.remove('hidden');

        if (!chapitres.length) {
            grid.innerHTML = '<div class="col-span-2 py-6 text-center text-[13px] text-[#77819A]">Aucun chapitre disponible.</div>';
            return;
        }

        paintGrid();
    }, { once: true });

    function paintGrid() {
        const grid = root.querySelector('[data-setup-grid]');
        grid.innerHTML = chapitres.map((c) => {
            const active = String(c.id) === String(selected);
            return `
                <button type="button" data-chapitre-option="${c.id}" class="flex items-center justify-between gap-2.5 rounded-[13px] border p-4 text-left transition ${active ? 'border-[#5E7F12] bg-[#F3F8E3]' : 'border-[#E4E7EF] bg-white hover:border-[#C9CFDD]'}">
                    <span>
                        <span class="block text-[14.5px] font-medium text-[#12161F]">${escapeHtml(c.titre)}</span>
                        <span class="mg-mono mt-1 block text-[10px] text-[#77819A]">${c.niveau ? escapeHtml(c.niveau.nom) : ''}</span>
                    </span>
                    <span class="text-[15px] text-[${active ? '#5E7F12' : 'transparent'}]">✓</span>
                </button>
            `;
        }).join('');

        grid.querySelectorAll('[data-chapitre-option]').forEach((btn) => {
            btn.addEventListener('click', () => {
                selected = btn.dataset.chapitreOption;
                root.querySelector('[data-setup-submit]').disabled = false;
                root.querySelector('[data-setup-hint]').textContent = 'Prêt à lancer le quiz.';
                paintGrid();
            });
        });

        if (selected) {
            root.querySelector('[data-setup-submit]').disabled = false;
            root.querySelector('[data-setup-hint]').textContent = 'Prêt à lancer le quiz.';
        }
    }

    const steps = ["Lecture du chapitre…", "Recherche du quiz correspondant…", "Préparation des questions…"];

    root.querySelector('[data-setup-submit]').addEventListener('click', async () => {
        if (!selected) return;
        root.querySelector('[data-setup-error]').classList.add('hidden');
        root.querySelector('[data-setup-view]').classList.add('hidden');
        root.querySelector('[data-generating-view]').classList.remove('hidden');

        const stepEl = root.querySelector('[data-generating-step]');
        const barEl = root.querySelector('[data-generating-bar]');
        let i = 0;
        const timer = setInterval(() => {
            i = Math.min(i + 1, steps.length - 1);
            stepEl.textContent = steps[i];
            barEl.style.width = `${Math.round(((i + 1) / steps.length) * 100)}%`;
        }, 550);

        const [{ ok, data }] = await Promise.all([
            apiFetch(`/quiz?id_chapitre=${selected}`),
            new Promise((r) => setTimeout(r, 1700)),
        ]);
        clearInterval(timer);

        const quizzes = ok ? (data.data || []) : [];
        if (quizzes.length) {
            window.location.href = `/app/quiz/${quizzes[0].id}/jouer`;
            return;
        }

        root.querySelector('[data-generating-view]').classList.add('hidden');
        root.querySelector('[data-setup-view]').classList.remove('hidden');
        const err = root.querySelector('[data-setup-error]');
        err.textContent = "Aucun quiz n'existe encore pour ce chapitre. Demande à ton formateur d'en créer un, ou choisis un autre chapitre.";
        err.classList.remove('hidden');
    });
});
</script>
@endpush
