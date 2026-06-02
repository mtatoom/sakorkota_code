<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-[#05070c] text-slate-200 h-full min-h-screen">

    <div class="min-h-screen w-full flex flex-col justify-center items-center px-4 py-12 bg-[#05070c]">
        <div class="w-full sm:max-w-md">
            {{ $slot }}
        </div>
    </div>

</body>
</html>
