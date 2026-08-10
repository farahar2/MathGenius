@extends('layouts.marketing')

@section('title', 'MathGenius — Les maths, enfin adaptées à toi')
@section('description', "Leçons, exercices corrigés et quiz générés par IA pour le Tronc Commun, la 1ère et la 2ème année Bac.")

@section('content')
    {{-- Top bar --}}
    <div data-auth-nav class="fixed inset-x-0 top-0 z-50 flex items-center justify-between gap-4 border-b border-[#E4E7EF] bg-white/80 px-6 py-4 backdrop-blur-lg sm:px-10">
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#C6F24E] to-[#38E1D4] font-semibold text-[#12161F] mg-mono">∑</span>
            <span class="text-lg font-semibold tracking-tight text-[#12161F]">MathGenius</span>
            <span class="mg-mono hidden rounded-full border border-[#D9D0FA] px-2 py-[3px] text-[10px] text-[#7C5CFF] sm:inline-block">IA · GÉNÉRATION DE QUIZ</span>
        </a>

        <div class="flex items-center gap-2">
            <a data-guest-only href="{{ route('login') }}" class="rounded-[10px] border border-[#DEE2EC] px-4 py-2 text-sm text-[#4B5568] transition hover:border-[#5E7F12] hover:text-[#12161F]">
                Connexion
            </a>
            <a data-guest-only href="{{ route('register') }}" class="rounded-[10px] bg-[#C6F24E] px-5 py-2 text-sm font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">
                Créer un compte
            </a>
            <a data-auth-only href="{{ route('app.dashboard') }}" class="hidden rounded-[10px] bg-[#C6F24E] px-5 py-2 text-sm font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">
                Mon tableau de bord
            </a>
        </div>
    </div>

    {{-- Hero --}}
    <section class="relative overflow-hidden px-6 pb-24 pt-40 text-center sm:px-10 sm:pt-48">
        <div class="mg-grid-bg pointer-events-none absolute inset-0"></div>
        <div class="mg-blob pointer-events-none absolute -top-40 left-1/2 h-[560px] w-[560px] -translate-x-1/2 rounded-full blur-3xl" style="background: radial-gradient(circle, #7C5CFF1F, transparent 65%);"></div>

        <div class="relative mx-auto max-w-3xl">
            <div class="mg-anim-rise mg-mono inline-flex items-center gap-2 rounded-full border border-[#DEE2EC] bg-white/90 px-4 py-[7px] text-[12.5px] text-[#9AA3BA]">
                <span class="h-1.5 w-1.5 rounded-full" style="background:#C6F24E; box-shadow:0 0 10px #C6F24E;"></span>
                Apprentissage personnalisé assisté par l'intelligence artificielle
            </div>

            <h1 class="mg-anim-rise mt-7 text-5xl font-bold leading-[0.98] tracking-tight text-[#12161F] sm:text-7xl" style="animation-delay:.08s">
                Les maths,<br>
                <span class="bg-gradient-to-r from-[#6B8F14] via-[#0E9E92] to-[#7C5CFF] bg-clip-text text-transparent">enfin adaptées à toi.</span>
            </h1>

            <p class="mg-anim-rise mx-auto mt-6 max-w-xl text-lg leading-relaxed text-[#55607A]" style="animation-delay:.15s">
                Leçons, exercices corrigés et quiz générés par IA pour le Tronc Commun, la 1<sup>ère</sup> et la 2<sup>ème</sup> année Bac.
                MathGenius évalue ton niveau réel, détecte tes lacunes et te dit exactement quoi réviser.
            </p>

            <div class="mg-anim-rise mt-9 flex flex-wrap items-center justify-center gap-3" style="animation-delay:.2s">
                <a href="{{ route('register') }}" class="rounded-xl bg-[#C6F24E] px-7 py-4 text-[15.5px] font-semibold text-[#12161F] shadow-[0_12px_40px_-12px_#C6F24E] transition hover:bg-[#DCFF7A]">
                    Commencer gratuitement
                </a>
                <a href="#comment-ca-marche" class="rounded-xl border border-[#DEE2EC] bg-[#F1F3F8] px-6 py-4 text-[15.5px] text-[#12161F] transition hover:border-[#7C5CFF]">
                    ▶ Comment ça marche
                </a>
            </div>

            <div class="mg-anim-fade mt-14 flex flex-wrap justify-center gap-10" style="animation-delay:.4s">
                <div>
                    <div class="mg-mono text-3xl text-[#12161F]">20</div>
                    <div class="mg-mono text-[11px] tracking-widest text-[#77819A]">QUESTIONS / QUIZ</div>
                </div>
                <div>
                    <div class="mg-mono text-3xl text-[#12161F]">3</div>
                    <div class="mg-mono text-[11px] tracking-widest text-[#77819A]">NIVEAUX SCOLAIRES</div>
                </div>
                <div>
                    <div class="mg-mono text-3xl text-[#12161F]">&lt;4s</div>
                    <div class="mg-mono text-[11px] tracking-widest text-[#77819A]">GÉNÉRATION IA</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Ticker --}}
    <div class="overflow-hidden border-y border-[#E4E7EF] bg-white py-3.5">
        @php
            $stack = ['LARAVEL 13', 'SANCTUM', 'MYSQL', 'GROQ', 'PEST', 'DOCKER', 'GITHUB ACTIONS'];
        @endphp
        <div class="mg-ticker-track flex w-[200%]">
            @for ($i = 0; $i < 2; $i++)
                <div class="mg-mono flex w-1/2 shrink-0 items-center gap-11 whitespace-nowrap text-[13px] text-[#9AA3B8]">
                    @foreach ($stack as $tech)
                        <span>{{ $tech }}</span><span>·</span>
                    @endforeach
                </div>
            @endfor
        </div>
    </div>

    {{-- How it works --}}
    <section id="comment-ca-marche" class="mx-auto max-w-6xl px-6 py-28 sm:px-10">
        <div class="mg-mono text-[11.5px] tracking-[.14em] text-[#7C5CFF]">01 — COMMENT ÇA MARCHE</div>
        <h2 class="mt-3 text-4xl font-semibold tracking-tight text-[#12161F] sm:text-5xl">Du chapitre à la recommandation</h2>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($pipeline as $step)
                <div class="rounded-2xl border border-[#E4E7EF] bg-[#F8FAFD] p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                    <div class="flex items-center justify-between">
                        <span class="mg-mono text-[11px] text-[#77819A]">{{ $step['n'] }}</span>
                        <span class="text-xl">{{ $step['glyph'] }}</span>
                    </div>
                    <div class="mt-4 text-[16.5px] font-semibold text-[#12161F]">{{ $step['title'] }}</div>
                    <div class="mt-2 text-[13.5px] leading-relaxed text-[#5C667E]">{{ $step['body'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Features --}}
    <section class="mx-auto max-w-6xl px-6 pb-28 sm:px-10">
        <div class="mg-mono text-[11.5px] tracking-[.14em] text-[#7C5CFF]">02 — FONCTIONNALITÉS</div>
        <h2 class="mt-3 text-4xl font-semibold tracking-tight text-[#12161F] sm:text-5xl">Tout le cahier des charges, livré</h2>

        <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach ($features as $feature)
                <div class="rounded-2xl border border-[#E4E7EF] bg-white p-6 shadow-[0_1px_2px_rgba(18,22,31,.05)] transition hover:border-[#C9CFDD] hover:bg-[#EEF1F7]">
                    <div class="mg-mono grid h-9 w-9 place-items-center rounded-[11px] bg-[#F2F6E4] text-base text-[#5E7F12]">{{ $feature['glyph'] }}</div>
                    <div class="mt-4 text-[17px] font-semibold text-[#12161F]">{{ $feature['title'] }}</div>
                    <div class="mt-2 text-[13.8px] leading-relaxed text-[#5C667E]">{{ $feature['body'] }}</div>
                </div>
            @endforeach
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="mx-auto max-w-6xl px-6 pb-28 sm:px-10">
        <div class="rounded-3xl border border-[#E4E7EF] bg-[#F4F6FA] px-8 py-16 text-center" style="background-image: radial-gradient(circle at 50% 0%, #7C5CFF14, transparent 70%);">
            <h2 class="text-3xl font-semibold tracking-tight text-[#12161F] sm:text-4xl">Prêt à connaître ton vrai niveau ?</h2>
            <p class="mx-auto mt-4 max-w-lg text-[#5C667E]">Crée ton compte, choisis ton chapitre, et laisse l'IA générer ton premier quiz de 20 questions.</p>
            <a href="{{ route('register') }}" class="mt-7 inline-block rounded-xl bg-[#C6F24E] px-8 py-4 text-[15.5px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A]">
                Créer mon compte élève
            </a>
        </div>

        <div class="mg-mono mt-10 flex flex-col justify-between gap-2 text-[11.5px] text-[#9AA3B8] sm:flex-row">
            <div>MATHGENIUS © {{ now()->year }} — PROJET DE FIN DE FORMATION</div>
            <div>BACKEND DEVELOPER LARAVEL AI AUGMENTED</div>
        </div>
    </section>
@endsection
