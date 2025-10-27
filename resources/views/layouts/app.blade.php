<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name', 'Laravel') }}</title>

	<!-- Bootstrap CSS (CDN) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Bootstrap Icons (uniquement les icônes) -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

	<!-- Vite (Tailwind / app.css + app.js) -->
	@vite(['resources/css/app.css', 'resources/js/app.js'])

	@stack('styles')
</head>
<body class="antialiased bg-gray-100 text-gray-900">
	<!-- Navigation -->
	@include('layouts.navigation')

	<!-- Page content -->
	<main class="container mx-auto px-4 py-6">
		@yield('content')
	</main>

	<!-- Bootstrap JS (CDN) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

	@stack('scripts')
</body>
</html>
