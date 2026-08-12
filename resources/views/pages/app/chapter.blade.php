@extends('layouts.app')

@section('title', 'Chapitre — MathGenius')

@section('content')
    <div data-page="chapter" data-chapitre-id="{{ $chapitreId }}" class="animate-[mgFade_.4s_ease_both]">
        <a href="{{ route('app.chapters') }}" class="mb-4 inline-block text-[13px] text-[#5C667E] transition hover:text-[#5E7F12]">← Tous les chapitres</a>

        <div data-chapter-loading class="py-16 text-center text-[13.5px] text-[#77819A]">Chargement du chapitre…</div>

        <div data-chapter-root class="hidden">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div>
                    <div data-chapter-meta class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">—</div>
                    <h1 data-chapter-title class="mt-3 text-[30px] font-semibold tracking-tight text-[#12161F] sm:text-[36px]">—</h1>
                </div>
                <a data-chapter-quiz-link href="#" class="whitespace-nowrap rounded-[11px] bg-[#7C5CFF] px-[22px] py-3.5 text-[14px] font-semibold text-white transition hover:bg-[#9179FF]">
                    ✦ Faire un quiz sur ce chapitre
                </a>
            </div>

            <p data-chapter-description class="mt-3 max-w-2xl text-[14.5px] leading-relaxed text-[#5C667E]"></p>

            <div class="mt-6 flex w-fit gap-1.5 rounded-xl border border-[#E4E7EF] bg-[#F1F3F8] p-1">
                <button type="button" data-tab="lessons" class="rounded-[9px] px-[18px] py-2.5 text-[13.5px] transition">Leçons</button>
                <button type="button" data-tab="exercises" class="rounded-[9px] px-[18px] py-2.5 text-[13.5px] transition">Exercices</button>
            </div>

            <div data-panel="lessons" class="mt-6 flex flex-col gap-3.5"></div>
            <div data-panel="exercises" class="mt-6 hidden flex-col gap-3.5"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="chapter"]');
    if (!root) return;
    const { apiFetch, escapeHtml } = window.MG;
    const chapitreId = root.dataset.chapitreId;
    let activeTab = 'lessons';

    document.addEventListener('mg:user-ready', async () => {
        const [chapRes, leconsRes, quizRes] = await Promise.all([
            apiFetch(`/chapitres/${chapitreId}`),
            apiFetch(`/chapitres/${chapitreId}/lecons`),
            apiFetch(`/quiz?id_chapitre=${chapitreId}`),
        ]);

        if (!chapRes.ok) {
            root.querySelector('[data-chapter-loading]').textContent = "Ce chapitre est introuvable.";
            return;
        }

        const chapitre = chapRes.data.data;
        const lecons = leconsRes.ok ? (leconsRes.data.data || []) : [];
        const quizzes = quizRes.ok ? (quizRes.data.data || []) : [];

        root.querySelector('[data-chapter-loading]').classList.add('hidden');
        root.querySelector('[data-chapter-root]').classList.remove('hidden');
        root.querySelector('[data-chapter-meta]').textContent = (chapitre.niveau ? chapitre.niveau.nom : 'Niveau') + ` · CH-${String(chapitre.ordre ?? chapitre.id).padStart(2, '0')}`;
        root.querySelector('[data-chapter-title]').textContent = chapitre.titre;
        root.querySelector('[data-chapter-description]').textContent = chapitre.description || '';

        const quizLink = root.querySelector('[data-chapter-quiz-link]');
        if (quizzes.length) {
            quizLink.href = `/app/quiz/${quizzes[0].id}/jouer`;
        } else {
            quizLink.href = `/app/quiz?chapitre=${chapitreId}`;
        }

        renderLessons(lecons);
        renderExercises(lecons);

        async function renderExercises(lessonList) {
            const panel = root.querySelector('[data-panel="exercises"]');
            if (!lessonList.length) {
                panel.innerHTML = '<div class="rounded-2xl border border-[#E4E7EF] bg-white p-8 text-center text-[13.5px] text-[#77819A]">Aucune leçon dans ce chapitre pour l\'instant.</div>';
                return;
            }

            const results = await Promise.all(lessonList.map((l) => apiFetch(`/lecons/${l.id}/exercices`)));
            const groups = lessonList.map((l, i) => ({
                lecon: l,
                exercices: results[i].ok ? (results[i].data.data || []) : [],
            })).filter((g) => g.exercices.length);

            if (!groups.length) {
                panel.innerHTML = '<div class="rounded-2xl border border-[#E4E7EF] bg-white p-8 text-center text-[13.5px] text-[#77819A]">Aucun exercice publié pour ce chapitre.</div>';
                return;
            }

            panel.innerHTML = groups.map((g) => `
                <div>
                    <div class="mg-mono mb-2.5 text-[10.5px] tracking-[.08em] text-[#77819A]">${escapeHtml(g.lecon.titre)}</div>
                    <div class="flex flex-col gap-3">
                        ${g.exercices.map((ex, idx) => `
                            <div class="rounded-[16px] border border-[#E4E7EF] bg-white p-5">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="mg-mono text-[10.5px] tracking-[.08em] text-[#77819A]">EXERCICE ${idx + 1}</div>
                                    <button type="button" data-toggle-solution class="whitespace-nowrap rounded-[9px] border border-[#DEE2EC] px-3.5 py-2 text-[11px] text-[#4B5568] transition hover:border-[#5E7F12] hover:text-[#12161F]">Voir le corrigé</button>
                                </div>
                                <div class="mt-2.5 text-[14.5px] leading-relaxed text-[#12161F]">${escapeHtml(ex.enonce)}</div>
                                <div data-solution class="mt-4 hidden rounded-[13px] border border-[#DDE9CB] bg-[#F4F6FA] p-4">
                                    <div class="mg-mono mb-2 text-[10.5px] tracking-[.08em] text-[#5E7F12]">CORRIGÉ</div>
                                    <div class="whitespace-pre-line text-[14px] leading-relaxed text-[#3B4557]">${escapeHtml(ex.correction)}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `).join('');

            panel.querySelectorAll('[data-toggle-solution]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    const sol = btn.closest('div').parentElement.querySelector('[data-solution]');
                    const open = !sol.classList.contains('hidden');
                    sol.classList.toggle('hidden');
                    btn.textContent = open ? 'Voir le corrigé' : 'Masquer';
                });
            });
        }

        function renderLessons(list) {
            const panel = root.querySelector('[data-panel="lessons"]');
            if (!list.length) {
                panel.innerHTML = '<div class="rounded-2xl border border-[#E4E7EF] bg-white p-8 text-center text-[13.5px] text-[#77819A]">Aucune leçon publiée pour ce chapitre.</div>';
                return;
            }
            panel.innerHTML = list.map((l) => `
                <div class="rounded-[18px] border border-[#E4E7EF] bg-white p-[26px]">
                    <div class="text-[19px] font-semibold tracking-tight text-[#12161F]">${escapeHtml(l.titre)}</div>
                    <div class="mt-3.5 whitespace-pre-line text-[14.5px] leading-relaxed text-[#3B4557]">${escapeHtml(l.contenu)}</div>
                </div>
            `).join('');
        }
    }, { once: true });

    function paintTabs() {
        root.querySelectorAll('[data-tab]').forEach((btn) => {
            const active = btn.dataset.tab === activeTab;
            btn.className = 'rounded-[9px] px-[18px] py-2.5 text-[13.5px] transition ' + (active ? 'bg-[#5E7F12] text-white' : 'text-[#5C667E]');
        });
        root.querySelectorAll('[data-panel]').forEach((panel) => {
            panel.classList.toggle('hidden', panel.dataset.panel !== activeTab);
            panel.classList.toggle('flex', panel.dataset.panel === activeTab);
            panel.classList.toggle('flex-col', panel.dataset.panel === activeTab);
        });
    }

    root.querySelectorAll('[data-tab]').forEach((btn) => {
        btn.addEventListener('click', () => { activeTab = btn.dataset.tab; paintTabs(); });
    });
    paintTabs();
});
</script>
@endpush
