@extends('layouts.app')

@section('title', 'Résultat — MathGenius')

@section('content')
    <div data-page="result" data-tentative-id="{{ $tentativeId }}" class="animate-[mgFade_.4s_ease_both]">
        <div data-result-loading class="py-16 text-center text-[13.5px] text-[#77819A]">Chargement du résultat…</div>
        <div data-result-error class="hidden rounded-2xl border border-[#F3D8CE] bg-[#FDF2ED] p-8 text-center text-[13.5px] text-[#C4442A]"></div>

        <div data-result-root class="hidden">
            <div data-result-chapitre class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">RÉSULTAT</div>
            <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Ton résultat</h1>

            <div class="mt-6 grid gap-3.5 lg:grid-cols-[320px_1fr]">
                <div class="rounded-[20px] border border-[#E4E7EF] bg-white p-[30px] text-center shadow-[0_1px_2px_rgba(18,22,31,.05)]" style="background-image: radial-gradient(circle at 50% 0%, #C6F24E2E, transparent 70%);">
                    <svg viewBox="0 0 120 120" class="mx-auto h-[180px] w-[180px]" style="transform:rotate(-90deg);">
                        <circle cx="60" cy="60" r="52" fill="none" stroke="#E4E7EF" stroke-width="9"></circle>
                        <circle data-result-ring cx="60" cy="60" r="52" fill="none" stroke="#5E7F12" stroke-width="9" stroke-linecap="round" stroke-dasharray="327" stroke-dashoffset="327" style="transition:stroke-dashoffset 1.1s ease;"></circle>
                    </svg>
                    <div data-result-score class="-mt-[118px] text-[40px] font-semibold tracking-tight text-[#12161F]">—</div>
                    <div data-result-level class="mg-mono mt-[74px] inline-flex rounded-full px-4 py-2 text-[12px]">—</div>
                    <div class="mg-mono mt-4 flex justify-center gap-5 text-[11px] text-[#77819A]">
                        <div data-result-correct>—</div>
                        <div data-result-wrong>—</div>
                        <div data-result-time>—</div>
                    </div>
                </div>

                <div class="flex flex-col gap-3.5">
                    <div class="rounded-[20px] border border-[#D9D0FA] bg-white p-[26px]" style="background-image: radial-gradient(circle at 100% 0%, #7C5CFF14, transparent 70%);">
                        <div class="mb-3.5 text-[16.5px] font-semibold text-[#12161F]">Analyse de performance</div>
                        <div data-result-analysis class="text-[14.5px] leading-relaxed text-[#3B4557]"></div>
                    </div>

                    <div class="rounded-[20px] border border-[#E4E7EF] bg-white p-[26px]">
                        <div class="mb-[18px] text-[16px] font-semibold text-[#12161F]">Maîtrise par notion</div>
                        <div data-result-topics class="flex flex-col gap-3.5"></div>
                    </div>
                </div>
            </div>

            <div class="mt-4 flex flex-wrap gap-3">
                <a href="{{ route('app.dashboard') }}" class="rounded-[11px] bg-[#C6F24E] px-6 py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">Retour au tableau de bord</a>
                <a href="{{ route('app.quiz.setup') }}" class="rounded-[11px] border border-[#DEE2EC] bg-[#F1F3F8] px-[22px] py-3.5 text-[14.5px] text-[#12161F] transition hover:border-[#7C5CFF]">✦ Nouveau quiz</a>
                <button type="button" data-result-toggle-review class="rounded-[11px] border border-[#DEE2EC] px-[22px] py-3.5 text-[14.5px] text-[#5C667E] transition hover:text-[#12161F]">Voir le détail des réponses</button>
            </div>

            <div data-result-review class="mt-4 hidden flex-col gap-2.5"></div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="result"]');
    if (!root) return;
    const { apiFetch, escapeHtml, fmtDuration } = window.MG;
    const tentativeId = root.dataset.tentativeId;

    document.addEventListener('mg:user-ready', async () => {
        const { ok, status, data } = await apiFetch(`/tentatives/${tentativeId}`);
        root.querySelector('[data-result-loading]').classList.add('hidden');

        if (!ok) {
            const err = root.querySelector('[data-result-error]');
            err.textContent = status === 403
                ? "Ce résultat ne t'appartient pas."
                : "Ce résultat est introuvable.";
            err.classList.remove('hidden');
            return;
        }

        const t = data.data;
        root.querySelector('[data-result-root]').classList.remove('hidden');

        const questionsById = {};
        (t.quiz && t.quiz.questions ? t.quiz.questions : []).forEach((q) => { questionsById[q.id] = q; });

        const total = (t.quiz && t.quiz.questions) ? t.quiz.questions.length : (t.reponses || []).length;
        const correct = Number(t.score) || 0;
        const wrong = Math.max(0, total - correct);
        const pct = Math.round(Number(t.score_pct) || 0);

        root.querySelector('[data-result-chapitre]').textContent = t.quiz && t.quiz.lecon ? t.quiz.lecon.titre.toUpperCase() : 'RÉSULTAT';
        root.querySelector('[data-result-score]').textContent = `${correct}/${total}`;
        root.querySelector('[data-result-correct]').textContent = `${correct} JUSTES`;
        root.querySelector('[data-result-wrong]').textContent = `${wrong} FAUSSES`;
        root.querySelector('[data-result-time]').textContent = t.completed_at ? new Date(t.completed_at).toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' }) : '—';

        const ring = root.querySelector('[data-result-ring]');
        ring.setAttribute('stroke-dashoffset', String(327 - (pct / 100) * 327));

        const level = pct >= 75 ? 'AVANCÉ' : pct >= 45 ? 'INTERMÉDIAIRE' : 'DÉBUTANT';
        const levelColors = pct >= 75 ? ['#F3F8E3', '#5E7F12'] : pct >= 45 ? ['#E6F7F5', '#0E9E92'] : ['#FDF2ED', '#C4442A'];
        const levelEl = root.querySelector('[data-result-level]');
        levelEl.textContent = `NIVEAU ${level}`;
        levelEl.style.background = levelColors[0];
        levelEl.style.color = levelColors[1];
        ring.setAttribute('stroke', levelColors[1]);

        const perNotion = {};
        (t.reponses || []).forEach((r) => {
            const q = questionsById[r.id_question];
            const notion = (q && q.notion) || 'Général';
            perNotion[notion] = perNotion[notion] || { ok: 0, n: 0 };
            perNotion[notion].n += 1;
            if (r.est_correcte) perNotion[notion].ok += 1;
        });

        const topics = Object.entries(perNotion).map(([name, v]) => ({
            name, pct: Math.round((v.ok / v.n) * 100),
        }));

        root.querySelector('[data-result-topics]').innerHTML = topics.length
            ? topics.map((tp) => {
                const color = tp.pct >= 75 ? '#5E7F12' : tp.pct >= 45 ? '#0E9E92' : '#C4442A';
                return `
                    <div>
                        <div class="mb-1.5 flex justify-between text-[13.5px]">
                            <div>${escapeHtml(tp.name)}</div>
                            <div class="mg-mono" style="color:${color}">${tp.pct}%</div>
                        </div>
                        <div class="h-[5px] overflow-hidden rounded-full bg-[#E4E7EF]">
                            <div class="mg-bar-fill h-full rounded-full" style="background:${color};width:${tp.pct}%"></div>
                        </div>
                    </div>
                `;
            }).join('')
            : '<div class="text-[13px] text-[#77819A]">Pas assez de données pour ce quiz.</div>';

        const weak = topics.filter((tp) => tp.pct < 60).map((tp) => tp.name);
        const strong = topics.filter((tp) => tp.pct >= 75).map((tp) => tp.name);
        let analysis = `Tu obtiens ${correct}/${total} (${pct}%) sur ce quiz, ce qui te situe au niveau ${level.toLowerCase()}. `;
        if (strong.length) analysis += `Tu maîtrises bien : ${strong.join(', ')}. `;
        if (weak.length) analysis += `Concentre ta prochaine révision sur : ${weak.join(', ')}.`;
        else if (!strong.length) analysis += `Continue à t'entraîner régulièrement sur ce chapitre.`;
        root.querySelector('[data-result-analysis]').textContent = analysis;

        const reviewWrap = root.querySelector('[data-result-review]');
        const letters = ['A', 'B', 'C', 'D'];
        reviewWrap.innerHTML = (t.reponses || []).map((r) => {
            const q = questionsById[r.id_question];
            if (!q) return '';
            const optionLabel = (letter) => ({ A: q.option_a, B: q.option_b, C: q.option_c, D: q.option_d }[letter]);
            const color = r.est_correcte ? '#5E7F12' : '#C4442A';
            return `
                <div class="rounded-[14px] border border-[#E4E7EF] bg-white p-[18px]">
                    <div class="flex items-start gap-3">
                        <div class="mg-mono min-w-[70px] text-[11px]" style="color:${color}">${r.est_correcte ? 'JUSTE' : 'FAUX'}</div>
                        <div class="min-w-0 flex-1">
                            <div class="text-[14.5px] text-[#12161F]">${escapeHtml(q.question)}</div>
                            <div class="mt-2 text-[12.8px] text-[#5C667E]">
                                Ta réponse : <span style="color:${color}">${escapeHtml(optionLabel(r.reponse_eleve) || r.reponse_eleve)}</span>
                                · Correcte : <span class="text-[#5E7F12]">${escapeHtml(optionLabel(q.bonne_reponse) || q.bonne_reponse)}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        }).join('');

        root.querySelector('[data-result-toggle-review]').addEventListener('click', (e) => {
            const open = !reviewWrap.classList.contains('hidden');
            reviewWrap.classList.toggle('hidden');
            reviewWrap.classList.toggle('flex');
            reviewWrap.classList.toggle('flex-col');
            e.target.textContent = open ? 'Voir le détail des réponses' : 'Masquer le détail';
        });
    }, { once: true });
});
</script>
@endpush
