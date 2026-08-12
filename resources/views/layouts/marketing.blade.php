<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'MathGenius')</title>
    <meta name="description" content="@yield('description', "Plateforme d'apprentissage des mathématiques assistée par IA — leçons, exercices et quiz personnalisés.")">


    @include('partials.head-assets')

    @stack('styles')
</head>
<body class="mg-body min-h-screen">
    @yield('content')

    @stack('scripts')
</body>
</html>
