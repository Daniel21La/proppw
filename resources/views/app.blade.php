<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#070709]">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#070709">
        <meta name="robots" content="index, follow">

        <!-- Default SEO Meta Tags -->
        <meta name="description" content="QUANTUM STREAMLINE - Platform pemesanan rental mobil mewah online terpercaya di Indonesia. Layanan sewa Lepas Kunci & Dengan Sopir armada Alphard, Camry, Fortuner, dan sportscar.">
        <meta name="keywords" content="rental mobil mewah, sewa mobil lepas kunci, rental alphard, sewa camaro, sewa mobil dengan sopir, quantum streamline">
        <meta name="author" content="QUANTUM STREAMLINE">

        <!-- OpenGraph & Social Sharing Fallbacks -->
        <meta property="og:site_name" content="QUANTUM STREAMLINE">
        <meta property="og:type" content="website">
        <meta property="og:locale" content="id_ID">
        <meta name="twitter:card" content="summary_large_image">

        <title inertia>{{ config('app.name', 'QUANTUM STREAMLINE - Luxury Car Rental') }}</title>

        <!-- Google Fonts: Plus Jakarta Sans with Preconnect -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts and Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased text-white bg-[#070709] min-h-full flex flex-col selection:bg-red-600 selection:text-white">
        @inertia
    </body>
</html>
