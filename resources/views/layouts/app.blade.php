<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Mon App')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Styles personnalisés -->
    <style>
        .card {
            transition: all 0.3s ease;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .card:hover {
            transform: translateY(calc(-1 * 8px));
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .card-img-top {
            transition: transform 0.5s ease;
        }
        .card:hover .card-img-top {
            transform: scale(1.05);
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(calc(-1 * 1px));
        }
        .hover-opacity {
            transition: opacity 0.3s ease;
        }
        .card:hover .hover-opacity {
            opacity: 1 !important;
        }
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .shadow-custom {
            box-shadow: 0 calc(10px) calc(30px) rgba(0,0,0,0.1);
        }
        /* Nouveau : Amélioration des cartes colorées */
        .card-header {
            position: relative;
            z-index: 3;
        }
        .card-header h6 {
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }
        .rounded-circle {
            transition: transform 0.2s ease;
        }
        .rounded-circle:hover {
            transform: scale(1.2);
        }
        /* Logo au-dessus de tout */
        .logo-overlay {
            z-index: 15 !important;
            position: relative;
        }
    </style>

    @yield('styles')
</head>
<body class="bg-light">
    <div class="container py-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
