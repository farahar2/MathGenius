@extends('layouts.app')

@section('title', 'Mes résultats — MathGenius')

@section('content')
    <div data-page="results" class="animate-[mgFade_.4s_ease_both]">
        <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">HISTORIQUE</div>
        <h1 class="mt-3 text-[32px] font-semibold tracking-tight text-[#12161F] sm:text-[38px]">Mes résultats</h1>

        <div data-results-empty class="hidden mt-8 rounded-2xl border border-[#E4E7EF] bg-white p-10 text-center">
            <p class="text-[14px] text-[#5C667E]">Tu n'as pas encore réalisé de quiz.</p>
            <a href="{{ route('app.quiz.setup') }}" class="mt-4 inline-block rounded-[11px] bg-[#C6F24E] px-6 py-3 text-[14px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">Faire mon premier quiz</a>
        </div>

        <div data-results-list class="mt-6 flex flex-col gap-2.5"></div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="results"]');
    if (!root) return;
    const { apiFetch, escapeHtml, fmtDate } = window.MG;

    document.addEventListener('mg:user-ready', async () => {
        const { ok, data } = await apiFetch('/tentatives');
        const list = ok ? (data.data || []) : [];

        if (!list.length) {
            root.querySelector('[data-results-empty]').classList.remove('hidden');
            return;
        }

        root.querySelector('[data-results-list]').innerHTML = list.map((t) => {
            const pct = Math.round(Number(t.score_pct) || 0);
            const color = pct >= 70 ? '#5E7F12' : pct >= 45 ? '#0E9E92' : '#C4442A';
            const bg = pct >= 70 ? '#F3F8E3' : pct >= 45 ? '#E6F7F5' : '#FDF2ED';
            return `
                <a href="/app/resultats/${t.id}" class="flex items-center justify-between gap-4 rounded-[16px] border border-[#E4E7EF] bg-white p-5 transition hover:border-[#C9CFDD] hover:bg-[#EEF1F7]">
                    <div class="min-w-0">
                        <div class="truncate text-[15px] font-medium text-[#12161F]">${escapeHtml(t.quiz && t.quiz.lecon ? t.quiz.lecon.titre : 'Quiz')}</div>
                        <div class="mg-mono mt-1.5 text-[10.5px] text-[#77819A]">${escapeHtml(fmtDate(t.completed_at))} · ${t.score} bonne${t.score > 1 ? 's' : ''} réponse${t.score > 1 ? 's' : ''}</div>
                    </div>
                    <div class="mg-mono shrink-0 rounded-full px-3 py-1.5 text-[13px]" style="background:${bg};color:${color}">${pct}%</div>
                </a>
            `;
        }).join('');
    }, { once: true });
});
</script>
@endpush
