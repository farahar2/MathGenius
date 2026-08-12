@extends('layouts.marketing')

@section('title', 'Tableau de bord — MathGenius')

@section('content')
    <div class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-6 py-16 text-center">
        <a href="{{ route('landing') }}" class="mb-10 flex items-center gap-3">
            <span class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#C6F24E] to-[#38E1D4] font-semibold text-[#12161F] mg-mono">∑</span>
            <span class="text-lg font-semibold tracking-tight text-[#12161F]">MathGenius</span>
        </a>

        <div data-dashboard-loading class="flex flex-col items-center gap-4">
            <div class="mg-spin h-10 w-10 rounded-full border-2 border-[#DDE1EB] border-t-[#7C5CFF]"></div>
            <p class="mg-mono text-[12px] text-[#77819A]">Vérification de la session…</p>
        </div>

        <div data-dashboard-root class="hidden w-full">
            <div class="mg-mono text-[11px] tracking-[.14em] text-[#7C5CFF]">ESPACE ÉLÈVE</div>
            <h1 class="mt-3 text-3xl font-semibold tracking-tight text-[#12161F]">
                Salut <span data-user-name>—</span> 👋
            </h1>

            <div class="mt-8 rounded-2xl border border-[#E4E7EF] bg-white p-8 text-left shadow-[0_1px_2px_rgba(18,22,31,.05)]">
                <div class="mg-mono text-[10.5px] tracking-[.1em] text-[#77819A]">CONNECTÉ EN TANT QUE</div>
                <div class="mt-2 text-lg font-medium text-[#12161F]" data-user-email>—</div>
                <div class="mt-1 text-sm text-[#5C667E]" data-user-role>—</div>

                <div class="mt-6 rounded-[13px] border border-[#D9D0FA] bg-[#F8FAFD] p-5 text-sm leading-relaxed text-[#5C667E]">
                    Le tableau de bord complet (chapitres, quiz IA, résultats, espace formateur) arrive dans une prochaine étape.
                    Cette page confirme que l'authentification Sanctum est bien reliée à <code class="mg-mono text-[#7C5CFF]">/api/me</code>.
                </div>
            </div>

            <div class="mt-6 flex justify-center gap-3">
                <a href="{{ route('landing') }}" class="rounded-[11px] border border-[#DEE2EC] px-5 py-3 text-sm text-[#4B5568] transition hover:border-[#5E7F12] hover:text-[#12161F]">
                    Retour à l'accueil
                </a>
                <button data-logout type="button" class="rounded-[11px] bg-[#C4442A] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#D9553A] disabled:opacity-60">
                    Déconnexion
                </button>
            </div>
        </div>
    </div>
@endsection
