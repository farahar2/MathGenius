@extends('layouts.app')

@section('title', 'Tableau de bord — MathGenius')

@section('content')
    <div data-page="dashboard" class="animate-[mgFade_.4s_ease_both]">
        <div class="flex flex-wrap items-end justify-between gap-5">
            <div>
                <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">TABLEAU DE BORD</div>
                <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">
                    Salut <span data-dash-firstname>—</span> 👋
                </h1>
                <p data-dash-line class="mt-2 text-[15px] text-[#5C667E]">Chargement de ton activité…</p>
            </div>
            <a href="{{ route('app.quiz.setup') }}" class="whitespace-nowrap rounded-[11px] bg-[#C6F24E] px-6 py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">
                ✦ Quiz IA
            </a>
        </div>

        <div class="mt-7 grid grid-cols-2 gap-3.5 sm:grid-cols-4">
            <div class="rounded-2xl border border-[#E4E7EF] bg-white p-5 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">SCORE MOYEN</div>
                <div data-stat-avg class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F]">—</div>
                <div data-stat-avg-hint class="mt-1.5 text-[12px] text-[#77819A]">&nbsp;</div>
            </div>
            <div class="rounded-2xl border border-[#E4E7EF] bg-white p-5 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">MEILLEUR SCORE</div>
                <div data-stat-best class="mt-3 text-[32px] font-semibold tracking-tight text-[#5E7F12]">—</div>
                <div data-stat-best-hint class="mt-1.5 text-[12px] text-[#77819A]">&nbsp;</div>
            </div>
            <div class="rounded-2xl border border-[#E4E7EF] bg-white p-5 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">QUIZ RÉALISÉS</div>
                <div data-stat-count class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F]">—</div>
                <div class="mt-1.5 text-[12px] text-[#77819A]">au total</div>
            </div>
            <div class="rounded-2xl border border-[#E4E7EF] bg-white p-5 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">CHAPITRES EXPLORÉS</div>
                <div data-stat-chapters class="mt-3 text-[32px] font-semibold tracking-tight text-[#0E9E92]">—</div>
                <div class="mt-1.5 text-[12px] text-[#77819A]">avec au moins un quiz</div>
            </div>
        </div>

        <div class="mt-3.5 grid gap-3.5 lg:grid-cols-[1.4fr_1fr]">
            <div class="rounded-[18px] border border-[#E4E7EF] bg-white p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mb-5 flex items-center justify-between">
                    <div class="text-[16.5px] font-semibold text-[#12161F]">Progression</div>
                    <div class="mg-mono text-[11px] text-[#77819A]">6 DERNIERS QUIZ</div>
                </div>
                <div data-dash-chart-empty class="hidden py-10 text-center text-[13.5px] text-[#77819A]">
                    Fais un premier quiz pour voir ta progression apparaître ici.
                </div>
                <svg data-dash-chart viewBox="0 0 520 200" class="h-[200px] w-full"></svg>
                <div data-dash-chart-labels class="mg-mono mt-2.5 flex justify-between text-[10.5px] text-[#8B95AC]"></div>
            </div>

            <div class="rounded-[18px] border border-[#D9D0FA] bg-white p-6 shadow-[0_1px_2px_rgba(124,92,255,.06)]" style="background-image: radial-gradient(circle at 100% 0%, #7C5CFF14, transparent 70%);">
                <div class="mb-4 flex items-center gap-2.5">
                    <div class="grid h-[22px] w-[22px] place-items-center rounded-[7px] bg-[#7C5CFF] text-[12px] text-white">✦</div>
                    <div class="text-[16.5px] font-semibold text-[#12161F]">Recommandations</div>
                </div>
                <div data-dash-reco-empty class="hidden py-6 text-center text-[13px] leading-relaxed text-[#77819A]">
                    Aucune recommandation pour l'instant. Ton formateur t'en enverra une après ton prochain quiz.
                </div>
                <div data-dash-reco-list class="flex flex-col gap-2.5"></div>
            </div>
        </div>

        <div class="mt-3.5 grid gap-3.5 lg:grid-cols-2">
            <div class="rounded-[18px] border border-[#E4E7EF] bg-white p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mb-4 text-[16.5px] font-semibold text-[#12161F]">Historique des quiz</div>
                <div data-dash-history-empty class="hidden py-6 text-center text-[13.5px] text-[#77819A]">
                    Aucun quiz réalisé pour l'instant.
                </div>
                <div data-dash-history-list class="flex flex-col"></div>
            </div>

            <div class="flex flex-col gap-3.5">
                <div class="rounded-[18px] border border-[#E4E7EF] bg-white p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                    <div class="mb-3.5 text-[15px] font-semibold text-[#5E7F12]">Chapitres maîtrisés</div>
                    <div data-dash-mastered class="flex flex-wrap gap-2">
                        <span class="text-[12.5px] text-[#77819A]">Pas encore de chapitre maîtrisé (≥ 70 % de moyenne).</span>
                    </div>
                </div>
                <div class="rounded-[18px] border border-[#E4E7EF] bg-white p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                    <div class="mb-3.5 text-[15px] font-semibold text-[#C4442A]">Chapitres à revoir</div>
                    <div data-dash-to-review class="flex flex-wrap gap-2">
                        <span class="text-[12.5px] text-[#77819A]">Rien à signaler pour l'instant.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="dashboard"]');
    if (!root) return;
    const { apiFetch, escapeHtml, fmtDate } = window.MG;

    function scoreColor(pct) {
        if (pct >= 70) return '#5E7F12';
        if (pct >= 45) return '#0E9E92';
        return '#C4442A';
    }

    document.addEventListener('mg:user-ready', async (e) => {
        const user = e.detail.user;
        root.querySelector('[data-dash-firstname]').textContent = user.prenom || user.name || '';

        const [tentativesRes, recosRes, chapitresRes] = await Promise.all([
            apiFetch('/tentatives'),
            apiFetch('/recommandations'),
            apiFetch('/chapitres'),
        ]);

        const tentatives = tentativesRes.ok ? (tentativesRes.data.data || []) : [];
        const recos = recosRes.ok ? (recosRes.data.data || []) : [];
        const chapitres = chapitresRes.ok ? (chapitresRes.data.data || []) : [];
        const chapitreById = {};
        chapitres.forEach((c) => { chapitreById[c.id] = c; });

        renderLine(tentatives.length
            ? `${tentatives.length} quiz réalisé${tentatives.length > 1 ? 's' : ''} jusqu'ici.`
            : "Tu n'as pas encore fait de quiz. Choisis un chapitre pour commencer !");

        renderStats(tentatives, chapitreById);
        renderChart(tentatives);
        renderRecommendations(recos);
        renderHistory(tentatives, chapitreById);
        renderMastery(tentatives, chapitreById);

        function renderLine(text) {
            root.querySelector('[data-dash-line]').textContent = text;
        }

        function renderStats(list, byChapitre) {
            const pcts = list.map((t) => Number(t.score_pct) || 0);
            const avg = pcts.length ? Math.round(pcts.reduce((a, b) => a + b, 0) / pcts.length) : null;
            const best = pcts.length ? Math.round(Math.max(...pcts)) : null;
            const chaptersTouched = new Set(list.map((t) => t.quiz && t.quiz.id_chapitre).filter(Boolean));

            root.querySelector('[data-stat-avg]').textContent = avg === null ? '—' : `${avg}%`;
            root.querySelector('[data-stat-avg-hint]').textContent = pcts.length ? `sur ${pcts.length} quiz` : 'aucun quiz';
            root.querySelector('[data-stat-best]').textContent = best === null ? '—' : `${best}%`;
            root.querySelector('[data-stat-count]').textContent = String(list.length);
            root.querySelector('[data-stat-chapters]').textContent = String(chaptersTouched.size);
        }

        function renderChart(list) {
            const chart = root.querySelector('[data-dash-chart]');
            const labels = root.querySelector('[data-dash-chart-labels]');
            const empty = root.querySelector('[data-dash-chart-empty]');

            const last = list.slice(0, 6).slice().reverse();
            if (!last.length) {
                empty.classList.remove('hidden');
                chart.classList.add('hidden');
                return;
            }
            empty.classList.add('hidden');
            chart.classList.remove('hidden');

            const step = last.length > 1 ? 500 / (last.length - 1) : 0;
            const pts = last.map((t, i) => ({
                x: 10 + i * step,
                y: 180 - (Math.min(100, Math.max(0, Number(t.score_pct) || 0)) / 100) * 160,
            }));
            const line = pts.map((p) => `${p.x},${p.y}`).join(' ');
            const area = `10,180 ${line} ${10 + (last.length - 1) * step},180`;

            chart.innerHTML = `
                <line x1="0" y1="50" x2="520" y2="50" stroke="#E4E7EF"></line>
                <line x1="0" y1="100" x2="520" y2="100" stroke="#E4E7EF"></line>
                <line x1="0" y1="150" x2="520" y2="150" stroke="#E4E7EF"></line>
                <polyline points="${area}" fill="#5E7F1233" stroke="none"></polyline>
                <polyline points="${line}" fill="none" stroke="#5E7F12" stroke-width="2.5" stroke-linejoin="round" class="mg-dash-line"></polyline>
                ${pts.map((p) => `<circle cx="${p.x}" cy="${p.y}" r="4" fill="#FFFFFF" stroke="#5E7F12" stroke-width="2.5"></circle>`).join('')}
            `;
            labels.innerHTML = last.map((t) => `<div>${escapeHtml(fmtDate(t.completed_at))}</div>`).join('');
        }

        function renderRecommendations(list) {
            const wrap = root.querySelector('[data-dash-reco-list]');
            const empty = root.querySelector('[data-dash-reco-empty]');
            if (!list.length) { empty.classList.remove('hidden'); wrap.innerHTML = ''; return; }
            empty.classList.add('hidden');

            wrap.innerHTML = list.slice(0, 4).map((r) => `
                <a href="${r.id_chapitre ? '/app/chapters/' + r.id_chapitre : '#'}" class="block rounded-[13px] border border-[#E4E7EF] bg-[#F8FAFD] p-3.5 transition hover:border-[#7C5CFF]">
                    <div class="flex items-center justify-between gap-2.5">
                        <div class="text-[14px] font-medium text-[#12161F]">${escapeHtml((r.chapitre && r.chapitre.titre) || 'Général')}</div>
                        <div class="mg-mono rounded-[6px] px-1.5 py-0.5 text-[10px] ${r.is_lue ? 'bg-[#F1F3F8] text-[#77819A]' : 'bg-[#FDF2ED] text-[#C4442A]'}">${r.is_lue ? 'LU' : 'NOUVEAU'}</div>
                    </div>
                    <div class="mt-1.5 text-[12.5px] leading-relaxed text-[#5C667E]">${escapeHtml(r.message || '')}</div>
                </a>
            `).join('');
        }

        function renderHistory(list, byChapitre) {
            const wrap = root.querySelector('[data-dash-history-list]');
            const empty = root.querySelector('[data-dash-history-empty]');
            if (!list.length) { empty.classList.remove('hidden'); wrap.innerHTML = ''; return; }
            empty.classList.add('hidden');

            wrap.innerHTML = list.slice(0, 6).map((t) => {
                const chap = t.quiz && byChapitre[t.quiz.id_chapitre];
                const pct = Math.round(Number(t.score_pct) || 0);
                return `
                    <a href="/app/resultats/${t.id}" class="flex items-center justify-between gap-3 border-b border-[#EBEEF5] py-3 transition hover:bg-[#F8FAFD]">
                        <div class="min-w-0">
                            <div class="truncate text-[14px] text-[#12161F]">${escapeHtml(chap ? chap.titre : 'Quiz')}</div>
                            <div class="mg-mono mt-1 text-[10.5px] text-[#77819A]">${escapeHtml(fmtDate(t.completed_at))}</div>
                        </div>
                        <div class="mg-mono shrink-0 text-[16px] text-[${scoreColor(pct)}]">${pct}%</div>
                    </a>
                `;
            }).join('');
        }

        function renderMastery(list, byChapitre) {
            const perChapitre = {};
            list.forEach((t) => {
                const id = t.quiz && t.quiz.id_chapitre;
                if (!id) return;
                perChapitre[id] = perChapitre[id] || [];
                perChapitre[id].push(Number(t.score_pct) || 0);
            });

            const mastered = [];
            const toReview = [];
            Object.entries(perChapitre).forEach(([id, pcts]) => {
                const avg = pcts.reduce((a, b) => a + b, 0) / pcts.length;
                const chap = byChapitre[id];
                if (!chap) return;
                (avg >= 70 ? mastered : toReview).push(chap);
            });

            const masteredWrap = root.querySelector('[data-dash-mastered]');
            if (mastered.length) {
                masteredWrap.innerHTML = mastered.map((c) => `<a href="/app/chapters/${c.id}" class="rounded-full border border-[#DDE9CB] bg-[#F3F8E3] px-3 py-1.5 text-[12.5px] text-[#5E7F12]">${escapeHtml(c.titre)}</a>`).join('');
            }

            const toReviewWrap = root.querySelector('[data-dash-to-review]');
            if (toReview.length) {
                toReviewWrap.innerHTML = toReview.map((c) => `<a href="/app/chapters/${c.id}" class="rounded-full border border-[#F3D8CE] bg-[#FDF2ED] px-3 py-1.5 text-[12.5px] text-[#C4442A]">${escapeHtml(c.titre)}</a>`).join('');
            }
        }
    }, { once: true });
});
</script>
@endpush
