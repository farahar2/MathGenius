<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MathGenius')</title>
    <meta name="description" content="@yield('description', "Plateforme d'apprentissage des mathématiques assistée par IA — leçons, exercices et quiz personnalisés.")">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/site.js'])

    <style>
        :root {
            --mg-bg: #F7F8FB;
            --mg-ink: #12161F;
            --mg-lime: #C6F24E;
            --mg-lime-hover: #DCFF7A;
            --mg-teal: #38E1D4;
            --mg-teal-dark: #0E9E92;
            --mg-violet: #7C5CFF;
            --mg-violet-hover: #9179FF;
            --mg-olive: #5E7F12;
            --mg-danger: #C4442A;
            --mg-border: #E4E7EF;
            --mg-border-soft: #DEE2EC;
            --mg-muted: #5C667E;
            --mg-muted-soft: #77819A;
            --mg-panel: #F4F6FA;
            --mg-panel-alt: #F1F3F8;
        }

        body.mg-body {
            background: var(--mg-bg);
            color: var(--mg-ink);
            font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .mg-mono { font-family: 'IBM Plex Mono', ui-monospace, monospace; }

        @keyframes mgRise { from { opacity: 0; transform: translateY(24px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes mgFade { from { opacity: 0; } to { opacity: 1; } }
        @keyframes mgPulse { 0%, 100% { opacity: .22; transform: scale(1); } 50% { opacity: .5; transform: scale(1.1); } }
        @keyframes mgTicker { from { transform: translateX(0); } to { transform: translateX(-50%); } }
        @keyframes mgGrid { from { background-position: 0 0; } to { background-position: 0 64px; } }
        @keyframes mgSpin { to { transform: rotate(360deg); } }

        .mg-anim-rise { animation: mgRise .8s cubic-bezier(.2,.8,.2,1) both; }
        .mg-anim-fade { animation: mgFade 1s both; }
        .mg-grid-bg {
            background-image: linear-gradient(#E9ECF4 1px, transparent 1px), linear-gradient(90deg, #E9ECF4 1px, transparent 1px);
            background-size: 64px 64px;
            animation: mgGrid 6s linear infinite;
            opacity: .35;
        }
        .mg-blob { animation: mgPulse 7s ease-in-out infinite; }
        .mg-ticker-track { animation: mgTicker 26s linear infinite; }
        .mg-spin { animation: mgSpin 8s linear infinite; }

        @media (prefers-reduced-motion: reduce) {
            .mg-anim-rise, .mg-anim-fade, .mg-grid-bg, .mg-blob, .mg-ticker-track, .mg-spin { animation: none; }
        }
    </style>

    @stack('styles')
</head>
<body class="mg-body min-h-screen">
    @yield('content')

    @stack('scripts')
</body>
</html>
