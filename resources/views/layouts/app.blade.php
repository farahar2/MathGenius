<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MathGenius')</title>

    @include('partials.head-assets')

    @stack('styles')
</head>
<body class="mg-body min-h-screen" data-requires-auth>
    <div class="grid min-h-screen lg:grid-cols-[264px_1fr]">
        {{-- Sidebar --}}
        <aside class="flex h-screen flex-col gap-6 border-r border-[#E4E7EF] bg-white px-4 py-6 lg:sticky lg:top-0">
            <a href="{{ route('landing') }}" class="flex items-center gap-2.5 px-1.5">
                <span class="grid h-8 w-8 place-items-center rounded-[9px] bg-gradient-to-br from-[#C6F24E] to-[#38E1D4] font-semibold text-[#12161F] mg-mono">∑</span>
                <span class="text-[17px] font-semibold text-[#12161F]">MathGenius</span>
            </a>

            <nav class="flex flex-col gap-0.5">
                <div class="mg-mono px-2 pb-2 text-[10px] tracking-[.14em] text-[#8B95AC]">ESPACE ÉLÈVE</div>
                @foreach ([
                    ['route' => 'app.dashboard', 'label' => 'Tableau de bord', 'glyph' => '⬒'],
                    ['route' => 'app.chapters', 'label' => 'Chapitres', 'glyph' => '📘'],
                    ['route' => 'app.quiz.setup', 'label' => 'Quiz', 'glyph' => '✦'],
                    ['route' => 'app.results', 'label' => 'Mes résultats', 'glyph' => '📊'],
                    ['route' => 'app.profile', 'label' => 'Profil', 'glyph' => '◍'],
                ] as $item)
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-2.5 rounded-[10px] px-3 py-2.5 text-[14.5px] transition hover:bg-[#F2F6E4] {{ request()->routeIs($item['route'] . '*') ? 'bg-[#F2F6E4] text-[#5E7F12]' : 'text-[#5C667E]' }}">
                        <span class="mg-mono w-[18px] text-center text-[13px]">{{ $item['glyph'] }}</span>{{ $item['label'] }}
                    </a>
                @endforeach
            </nav>

            <nav data-formateur-nav class="hidden flex-col gap-0.5">
                <div class="mg-mono px-2 pb-2 text-[10px] tracking-[.14em] text-[#8B95AC]">ESPACE FORMATEUR</div>
                <a href="{{ route('app.admin') }}"
                   class="flex items-center gap-2.5 rounded-[10px] px-3 py-2.5 text-[14.5px] transition hover:bg-[#F2F6E4] {{ request()->routeIs('app.admin*') ? 'bg-[#E6F7F5] text-[#0E9E92]' : 'text-[#5C667E]' }}">
                    <span class="mg-mono w-[18px] text-center text-[13px]">⚙</span>Administration
                </a>
            </nav>

            <div class="mt-auto border-t border-[#E4E7EF] pt-4">
                <a href="{{ route('app.profile') }}" class="flex items-center gap-2.5 rounded-[10px] p-2 transition hover:bg-[#F2F6E4]">
                    <span data-user-initials class="grid h-8 w-8 shrink-0 place-items-center rounded-full bg-gradient-to-br from-[#7C5CFF] to-[#38E1D4] text-[13px] font-semibold text-[#12161F]">··</span>
                    <span class="min-w-0">
                        <span data-user-name class="block truncate text-[13.5px] font-medium text-[#12161F]">—</span>
                        <span data-user-level class="block text-[11px] text-[#77819A]">—</span>
                    </span>
                </a>
                <button type="button" data-logout class="mt-1 w-full rounded-[10px] px-2 py-2 text-left text-[12.5px] text-[#77819A] transition hover:text-[#C4442A] disabled:opacity-60">
                    ⏎ Déconnexion
                </button>
            </div>
        </aside>

        {{-- Main --}}
        <main class="min-w-0 px-6 py-8 sm:px-10 sm:py-9">
            <div class="mx-auto w-full max-w-[1120px]">
                @yield('content')
            </div>
        </main>
    </div>

    @stack('scripts')
</body>
</html>
