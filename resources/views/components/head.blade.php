<head lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Open Graph (Facebook) -->
    <meta property="og:url" content="https://palmoraindonesia.com">
    <meta property="og:type" content="website">
    <meta property="og:title" content="PALMORA Indonesia">
    <meta property="og:description" content="Jelajahi produk olahan sawit berkualitas dari PALMORA Indonesia. Komitmen terhadap keberlanjutan dan kualitas terbaik.">
    <meta property="og:image" content="https://palmoraindonesia.com/assets/og-palmora.jpg">
    <meta property="og:image:width" content="470">
    <meta property="og:image:height" content="470">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="palmoraindonesia.com">
    <meta property="twitter:url" content="https://palmoraindonesia.com">
    <meta name="twitter:title" content="PALMORA Indonesia">
    <meta name="twitter:description" content="Temukan minyak goreng, sabun, margarin, dan produk sawit lainnya dari PALMORA Indonesia.">

    <!-- Fonts & Styles -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Onest:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300..700&display=swap" rel="stylesheet">

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@2.4.1/dist/flowbite.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Meta Title & Description -->
    <title>{{ $title ?? 'Beranda' }} | PALMORA Indonesia</title>
    <meta name="description" content="PALMORA Indonesia menyediakan produk turunan sawit berkualitas tinggi untuk kebutuhan rumah tangga dan industri.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Vite -->
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>
