@extends('layouts.app')

@section('title', 'Chapitres — MathGenius')

@section('content')
    <div data-page="chapters" class="animate-[mgFade_.4s_ease_both]">
        <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">CONTENU PÉDAGOGIQUE</div>
        <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Chapitres</h1>

        <div data-chapters-filters class="mt-6 flex flex-wrap gap-2"></div>

        <div data-chapters-empty class="hidden mt-10 rounded-2xl border border-[#E4E7EF] bg-white p-10 text-center text-[13.5px] text-[#77819A]">
            Aucun chapitre disponible pour l'instant.
        </div>

        <div data-chapters-grid class="mt-6 grid gap-3.5 sm:grid-cols-2 lg:grid-cols-3"></div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="chapters"]');
    if (!root) return;
    const { apiFetch, escapeHtml } = window.MG;

    let state = { niveaux: [], chapitres: [], lessonCounts: {}, mastery: {}, filter: 'all' };

    document.addEventListener('mg:user-ready', async () => {
        const [niveauxRes, chapitresRes, leconsRes, tentativesRes] = await Promise.all([
            apiFetch('/niveaux'),
            apiFetch('/chapitres'),
            apiFetch('/lecons'),
            apiFetch('/tentatives'),
        ]);

        state.niveaux = niveauxRes.ok ? (niveauxRes.data.data || []) : [];
        state.chapitres = chapitresRes.ok ? (chapitresRes.data.data || []) : [];

        const lecons = leconsRes.ok ? (leconsRes.data.data || []) : [];
        lecons.forEach((l) => {
            state.lessonCounts[l.id_chapitre] = (state.lessonCounts[l.id_chapitre] || 0) + 1;
        });

        const tentatives = tentativesRes.ok ? (tentativesRes.data.data || []) : [];
        const grouped = {};
        tentatives.forEach((t) => {
            const id = t.quiz && t.quiz.id_chapitre;
            if (!id) return;
            grouped[id] = grouped[id] || [];
            grouped[id].push(Number(t.score_pct) || 0);
        });
        Object.entries(grouped).forEach(([id, pcts]) => {
            state.mastery[id] = Math.round(pcts.reduce((a, b) => a + b, 0) / pcts.length);
        });

        renderFilters();
        renderGrid();
    }, { once: true });

    function renderFilters() {
        const wrap = root.querySelector('[data-chapters-filters]');
        const chips = [{ id: 'all', nom: 'Tous' }, ...state.niveaux];
        wrap.innerHTML = chips.map((n) => {
            const active = state.filter === String(n.id);
            const cls = active
                ? 'border-[#5E7F12] bg-[#F3F8E3] text-[#5E7F12]'
                : 'border-[#DEE2EC] bg-transparent text-[#5C667E] hover:border-[#5E7F12]';
            return `<button type="button" data-filter="${n.id}" class="rounded-full border px-4 py-2 text-[13.5px] transition ${cls}">${escapeHtml(n.nom)}</button>`;
        }).join('');

        wrap.querySelectorAll('[data-filter]').forEach((btn) => {
            btn.addEventListener('click', () => {
                state.filter = btn.dataset.filter;
                renderFilters();
                renderGrid();
            });
        });
    }

    function renderGrid() {
        const grid = root.querySelector('[data-chapters-grid]');
        const empty = root.querySelector('[data-chapters-empty]');

        const list = state.chapitres.filter((c) => state.filter === 'all' || String(c.id_niveau) === state.filter);

        if (!list.length) {
            grid.innerHTML = '';
            empty.classList.remove('hidden');
            return;
        }
        empty.classList.add('hidden');

        grid.innerHTML = list.map((c) => {
            const niveau = state.niveaux.find((n) => n.id === c.id_niveau);
            const lessonCount = state.lessonCounts[c.id] || 0;
            const mastery = state.mastery[c.id];
            let badge = { label: 'PAS COMMENCÉ', bg: '#F1F3F8', fg: '#77819A' };
            if (mastery !== undefined) {
                badge = mastery >= 70
                    ? { label: 'MAÎTRISÉ', bg: '#F3F8E3', fg: '#5E7F12' }
                    : mastery >= 45
                        ? { label: 'EN COURS', bg: '#E6F7F5', fg: '#0E9E92' }
                        : { label: 'À REVOIR', bg: '#FDF2ED', fg: '#C4442A' };
            }
            const barColor = mastery === undefined ? '#DEE2EC' : (mastery >= 70 ? '#5E7F12' : mastery >= 45 ? '#0E9E92' : '#C4442A');
            const barWidth = mastery === undefined ? '0%' : `${mastery}%`;

            return `
                <a href="/app/chapters/${c.id}" class="block rounded-[18px] border border-[#E4E7EF] bg-white p-[22px] shadow-[0_1px_2px_rgba(18,22,31,.05)] transition hover:border-[#C9CFDD] hover:bg-[#EEF1F7]">
                    <div class="mb-4 flex items-center justify-between">
                        <span class="mg-mono text-[11px] text-[#77819A]">${niveau ? escapeHtml(niveau.nom) : '—'}</span>
                        <span class="mg-mono rounded-[6px] px-2 py-[3px] text-[10px]" style="background:${badge.bg};color:${badge.fg};">${badge.label}</span>
                    </div>
                    <div class="text-[17.5px] font-semibold tracking-tight text-[#12161F]">${escapeHtml(c.titre)}</div>
                    <div class="mt-2 line-clamp-2 text-[13px] leading-relaxed text-[#5C667E]">${escapeHtml(c.description || 'Aucune description pour ce chapitre.')}</div>
                    <div class="mg-mono mt-4 text-[10.5px] text-[#77819A]">${lessonCount} LEÇON${lessonCount > 1 ? 'S' : ''}</div>
                    <div class="mt-3.5 h-1 overflow-hidden rounded-full bg-[#E4E7EF]">
                        <div class="h-full rounded-full" style="background:${barColor};width:${barWidth};"></div>
                    </div>
                </a>
            `;
        }).join('');
    }
});
</script>
@endpush
