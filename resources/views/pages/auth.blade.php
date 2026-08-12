@extends('layouts.marketing')

@php
    $isSignup = $mode === 'register';
@endphp

@section('title', ($isSignup ? 'Créer un compte' : 'Connexion') . ' — MathGenius')

@section('content')
    <div data-auth-nav class="grid min-h-screen lg:grid-cols-[1.05fr_.95fr]">
        {{-- Left panel --}}
        <div class="relative hidden overflow-hidden border-r border-[#E4E7EF] bg-white p-14 lg:flex lg:flex-col lg:justify-between">
            <div class="mg-grid-bg pointer-events-none absolute inset-0"></div>
            <div class="mg-blob pointer-events-none absolute -bottom-40 -left-32 h-[520px] w-[520px] rounded-full blur-3xl" style="background: radial-gradient(circle, #38E1D426, transparent 65%);"></div>

            <a href="{{ route('landing') }}" class="relative flex items-center gap-3">
                <span class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#C6F24E] to-[#38E1D4] font-semibold text-[#12161F] mg-mono">∑</span>
                <span class="text-lg font-semibold tracking-tight text-[#12161F]">MathGenius</span>
            </a>

            <div class="relative">
                <div class="mg-mono text-[56px] leading-none text-[#DDE1EB]">∫∀∈π</div>
                <h2 class="mt-6 max-w-md text-4xl font-semibold leading-[1.15] tracking-tight text-[#12161F]">Ton niveau réel, en 20 questions.</h2>
                <p class="mt-4 max-w-md text-[15.5px] leading-relaxed text-[#5C667E]">L'IA analyse chaque erreur, situe ton niveau et construit ton plan de révision chapitre par chapitre.</p>
            </div>

            <div class="mg-mono relative text-[11px] text-[#9AA3B8]">CONNEXION SÉCURISÉE ET CHIFFRÉE</div>
        </div>

        {{-- Right panel --}}
        <div class="grid place-items-center px-6 py-16">
            <div class="w-full max-w-[400px]">
                <a href="{{ route('landing') }}" class="mb-8 flex items-center gap-3 lg:hidden">
                    <span class="grid h-9 w-9 place-items-center rounded-[10px] bg-gradient-to-br from-[#C6F24E] to-[#38E1D4] font-semibold text-[#12161F] mg-mono">∑</span>
                    <span class="text-lg font-semibold tracking-tight text-[#12161F]">MathGenius</span>
                </a>

                <div class="mb-7 flex gap-1 rounded-xl border border-[#E4E7EF] bg-[#F1F3F8] p-1">
                    <a href="{{ route('login') }}" class="flex-1 rounded-lg py-2.5 text-center text-sm transition {{ $isSignup ? 'text-[#5C667E]' : 'bg-[#5E7F12] text-[#12161F]' }}">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="flex-1 rounded-lg py-2.5 text-center text-sm transition {{ $isSignup ? 'bg-[#5E7F12] text-[#12161F]' : 'text-[#5C667E]' }}">
                        Inscription
                    </a>
                </div>

                <h1 class="text-[26px] font-semibold tracking-tight text-[#12161F]">
                    {{ $isSignup ? 'Créer ton compte' : 'Bon retour' }}
                </h1>
                <p class="mb-6 mt-1 text-sm text-[#5C667E]">
                    {{ $isSignup ? "Quelques informations et tu accèdes à tes leçons." : 'Connecte-toi pour retrouver ta progression.' }}
                </p>

                @if ($isSignup)
                    <form data-register-form class="flex flex-col gap-3.5" novalidate>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">PRÉNOM</label>
                                <input name="prenom" type="text" placeholder="Jean" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                                <p data-error-for="prenom" class="mt-1 text-xs text-[#C4442A]"></p>
                            </div>
                            <div>
                                <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">NOM</label>
                                <input name="name" type="text" placeholder="Dupont" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                                <p data-error-for="name" class="mt-1 text-xs text-[#C4442A]"></p>
                            </div>
                        </div>

                        <div>
                            <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">EMAIL</label>
                            <input name="email" type="email" placeholder="eleve@mathgenius.ma" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                            <p data-error-for="email" class="mt-1 text-xs text-[#C4442A]"></p>
                        </div>

                        <div>
                            <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">MOT DE PASSE</label>
                            <input name="password" type="password" placeholder="8 caractères minimum" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                            <p data-error-for="password" class="mt-1 text-xs text-[#C4442A]"></p>
                        </div>

                        <div>
                            <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">CONFIRMER LE MOT DE PASSE</label>
                            <input name="password_confirmation" type="password" placeholder="••••••••" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                        </div>

                        <p data-form-error class="hidden rounded-[10px] border border-[#F3D8CE] bg-[#FDF2ED] px-3.5 py-2.5 text-[13px] text-[#C4442A]"></p>

                        <button type="submit" class="mt-1 rounded-[11px] bg-[#C6F24E] py-3.5 text-[15px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:opacity-60">
                            Créer mon compte
                        </button>
                    </form>
                @else
                    <form data-login-form class="flex flex-col gap-3.5" novalidate>
                        <div>
                            <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">EMAIL</label>
                            <input name="email" type="email" placeholder="eleve@mathgenius.ma" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                            <p data-error-for="email" class="mt-1 text-xs text-[#C4442A]"></p>
                        </div>

                        <div>
                            <label class="mg-mono mb-1.5 block text-xs tracking-wide text-[#5C667E]">MOT DE PASSE</label>
                            <input name="password" type="password" placeholder="••••••••" class="w-full rounded-[11px] border border-[#DEE2EC] bg-[#F4F6FA] px-4 py-3 text-[14.5px] text-[#12161F] outline-none transition focus:border-[#5E7F12]">
                            <p data-error-for="password" class="mt-1 text-xs text-[#C4442A]"></p>
                        </div>

                        <p data-form-error class="hidden rounded-[10px] border border-[#F3D8CE] bg-[#FDF2ED] px-3.5 py-2.5 text-[13px] text-[#C4442A]"></p>

                        <button type="submit" class="mt-1 rounded-[11px] bg-[#C6F24E] py-3.5 text-[15px] font-semibold text-[#12161F] transition hover:bg-[#DCFF7A] disabled:opacity-60">
                            Se connecter
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection
