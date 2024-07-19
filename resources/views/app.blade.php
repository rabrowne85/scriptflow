<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-900 [color-scheme:dark]">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- HTML Meta Tags -->
    <title>{{ config('app.name', 'Laravel') }}</title>
    @inertiaHead

    @vite('resources/js/app.js')

    @production
        <!-- Fathom - beautiful, simple website analytics -->
        <script src="https://cdn.usefathom.com/script.js" data-site="{{ config('services.fathom.site ID') }}" defer></script>
        <!-- / Fathom -->
    @endproduction

</head>

<body class="h-full font-sans antialiased xl:overscroll-none w-full text-gray-100">
    <div class="mx-auto max-w-7xl px-0 sm:px-6 lg:px-8 min-h-full relative pb-28">
        <h1 class="absolute top-0 left-12 text-6xl h-full lg:left-20 xl:left-32 overflow-visible items-center justify-center select-none hidden md:flex lg:text-7xl xl:text-8xl font-semibold bg-gradient-to-b from-rose-400 leading-none to-rose-600 bg-clip-text text-transparent stroke-1 stroke-rose-50 [text-orientation:mixed] [writing-mode:vertical-rl] rotate-180 font-['MarckScript'] tracking-wider">ScriptFlow.cc</h1>
        @inertia
    </div>
</body>

</html>
