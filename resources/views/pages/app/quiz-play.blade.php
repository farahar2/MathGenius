@extends('layouts.app')

@section('title', 'Quiz — MathGenius')

@section('content')
    <div data-page="quiz-play" data-quiz-id="{{ $quizId }}" class="max-w-[820px] animate-[mgFade_.3s_ease_both]">
        <div data-quiz-loading class="py-16 text-center text-[13.5px] text-[#77819A]">Chargement du quiz…</div>
        <div data-quiz-error class="hidden rounded-2xl border border-[#F3D8CE] bg-[#FDF2ED] p-8 text-center text-[13.5px] text-[#C4442A]"></div>

        <div data-quiz-root class="hidden">
            <div class="mb-5 flex items-center justify-between">
                <div>
                    <div data-quiz-chapitre class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">—</div>
                    <div data-quiz-progress-label class="mt-2 text-[15px] text-[#5C667E]">Question 1 sur —</div>
                </div>
                <div class="flex items-center gap-4">
                    <div data-quiz-timer class="mg-mono text-[20px] text-[#12161F]">00:00</div>
                    <a href="{{ route('app.quiz.setup') }}" class="rounded-[9px] border border-[#DEE2EC] px-3.5 py-2.5 text-[12.5px] text-[#77819A] transition hover:border-[#C4442A] hover:text-[#C4442A]">Abandonner</a>
                </div>
            </div>

            <div class="mb-8 h-[5px] overflow-hidden rounded-full bg-[#E4E7EF]">
                <div data-quiz-progress-bar class="h-full rounded-full bg-gradient-to-r from-[#C6F24E] to-[#38E1D4] transition-[width]" style="width:0%"></div>
            </div>

            <div class="rounded-[20px] border border-[#E4E7EF] bg-white p-[34px] shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div data-quiz-notion class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">—</div>
                <div data-quiz-question class="mb-[26px] mt-3.5 text-[24px] leading-[1.4] tracking-tight text-[#12161F]"></div>
                <div data-quiz-options class="flex flex-col gap-2.5"></div>
            </div>

            <div class="mt-[22px] flex justify-between">
                <button type="button" data-quiz-prev class="rounded-[11px] border border-[#DEE2EC] bg-[#F1F3F8] px-[22px] py-3.5 text-[14px] text-[#4B5568] transition hover:border-[#5C667E] disabled:cursor-not-allowed disabled:opacity-50">← Précédent</button>
                <button type="button" data-quiz-next class="rounded-[11px] bg-[#C6F24E] px-[26px] py-3.5 text-[14.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:cursor-not-allowed disabled:opacity-60">Suivant →</button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.querySelector('[data-page="quiz-play"]');
    if (!root) return;
    const { apiFetch, escapeHtml, fmtDuration } = window.MG;
    const quizId = root.dataset.quizId;

    let quiz = null;
    let index = 0;
    let answers = {};
    let seconds = 0;
    let timerHandle = null;

    document.addEventListener('mg:user-ready', async () => {
        const { ok, data } = await apiFetch(`/quiz/${quizId}`);
        root.querySelector('[data-quiz-loading]').classList.add('hidden');

        if (!ok || !data.data.questions || !data.data.questions.length) {
            const err = root.querySelector('[data-quiz-error]');
            err.textContent = "Ce quiz est introuvable ou ne contient aucune question.";
            err.classList.remove('hidden');
            return;
        }

        quiz = data.data;
        root.querySelector('[data-quiz-root]').classList.remove('hidden');
        root.querySelector('[data-quiz-chapitre]').textContent = quiz.lecon ? quiz.lecon.titre : 'Quiz';

        timerHandle = setInterval(() => {
            seconds += 1;
            root.querySelector('[data-quiz-timer]').textContent = fmtDuration(seconds);
        }, 1000);

        renderQuestion();
    }, { once: true });

    function renderQuestion() {
        const total = quiz.questions.length;
        const q = quiz.questions[index];

        root.querySelector('[data-quiz-progress-label]').textContent = `Question ${index + 1} sur ${total}`;
        root.querySelector('[data-quiz-progress-bar]').style.width = `${Math.round(((index + 1) / total) * 100)}%`;
        root.querySelector('[data-quiz-notion]').textContent = (q.notion || 'QUESTION').toUpperCase();
        root.querySelector('[data-quiz-question]').textContent = q.question;

        const letters = ['A', 'B', 'C', 'D'];
        const labels = { A: q.option_a, B: q.option_b, C: q.option_c, D: q.option_d };
        const chosen = answers[q.id];

        root.querySelector('[data-quiz-options]').innerHTML = letters.map((letter) => {
            const active = chosen === letter;
            return `
                <button type="button" data-answer="${letter}" class="flex items-center gap-3.5 rounded-[13px] border p-4 text-left transition ${active ? 'border-[#5E7F12] bg-[#F3F8E3]' : 'border-[#E4E7EF] bg-[#F8FAFD] hover:border-[#C9CFDD]'}">
                    <span class="mg-mono grid h-7 w-7 shrink-0 place-items-center rounded-[8px] text-[12px] ${active ? 'bg-[#5E7F12] text-white' : 'bg-[#E4E7EF] text-[#5C667E]'}">${letter}</span>
                    <span class="text-[15.5px] ${active ? 'text-[#12161F]' : 'text-[#3B4557]'}">${escapeHtml(labels[letter] ?? '')}</span>
                </button>
            `;
        }).join('');

        root.querySelectorAll('[data-answer]').forEach((btn) => {
            btn.addEventListener('click', () => {
                answers[q.id] = btn.dataset.answer;
                renderQuestion();
            });
        });

        root.querySelector('[data-quiz-prev]').disabled = index === 0;
        const nextBtn = root.querySelector('[data-quiz-next]');
        nextBtn.textContent = index >= total - 1 ? 'Soumettre le quiz →' : 'Suivant →';
    }

    root.querySelector('[data-quiz-prev]').addEventListener('click', () => {
        if (index > 0) { index -= 1; renderQuestion(); }
    });

    root.querySelector('[data-quiz-next]').addEventListener('click', async () => {
        if (index < quiz.questions.length - 1) {
            index += 1;
            renderQuestion();
            return;
        }

        const nextBtn = root.querySelector('[data-quiz-next]');
        nextBtn.disabled = true;
        nextBtn.textContent = 'Correction en cours…';
        clearInterval(timerHandle);

        const reponses = Object.entries(answers).map(([id_question, reponse_eleve]) => ({
            id_question: Number(id_question),
            reponse_eleve,
        }));

        const { ok, data } = await apiFetch('/tentatives', {
            method: 'POST',
            body: JSON.stringify({ id_quiz: quiz.id, reponses }),
        });

        if (!ok) {
            nextBtn.disabled = false;
            nextBtn.textContent = 'Soumettre le quiz →';
            window.MG.showToast((data && data.message) || "Impossible d'envoyer tes réponses. Réessaie.");
            timerHandle = setInterval(() => { seconds += 1; root.querySelector('[data-quiz-timer]').textContent = fmtDuration(seconds); }, 1000);
            return;
        }

        window.location.href = `/app/resultats/${data.data.id}`;
    });
});
</script>
@endpush
