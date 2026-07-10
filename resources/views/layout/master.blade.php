<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FEL Dashboard</title>

    {{-- Vite assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Icônes et librairies externes --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

    <style>
        * {
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
        }

        body {
            display: flex;
            flex-direction: column;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background-color: #f3f4f6;
            color: #1f2937;
            margin: 0;
        }

        /* -------- Header -------- */
        .app-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 4rem;
            background-color: #ffffff;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
            z-index: 50;
        }

        .app-header-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .app-header-left i {
            font-size: 1.25rem;
            color: #4f46e5;
        }

        .app-header-title {
            font-weight: 600;
            font-size: 1.125rem;
        }

        .app-header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .app-header-right i {
            color: #4b5563;
            cursor: pointer;
            transition: color 0.15s ease-in-out;
        }

        .app-header-right i:hover {
            color: #4f46e5;
        }

        /* -------- Layout principal -------- */
        .app-body {
            display: flex;
            flex: 1 1 auto;
            padding-top: 4rem; /* compense le header fixe */
        }

        /* -------- Sidebar -------- */
        .app-sidebar {
            width: 16rem;
            background-color: #ffffff;
            box-shadow: 4px 0 6px -1px rgba(0, 0, 0, 0.05);
            border-right: 1px solid #e5e7eb;
            display: none;
            flex-direction: column;
        }

        @media (min-width: 768px) {
            .app-sidebar {
                display: flex;
            }
        }

        .app-sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .app-sidebar-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #4f46e5;
        }

        .app-sidebar-nav {
            flex: 1 1 auto;
            padding: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        /* -------- Contenu principal -------- */
        .app-main {
            flex: 1 1 auto;
            padding: 2rem;
            overflow-y: auto;
        }

        /* -------- Toasts -------- */
        .app-toasts {
            position: fixed;
            bottom: 1rem;
            right: 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
    </style>
</head>
<body>

    {{-- Header fixe --}}
    <header class="app-header">
        <div class="app-header-left">
            <i class="ri-dashboard-line"></i>
            <span class="app-header-title">FEL Dashboard</span>
        </div>
        <div class="app-header-right">
            <i class="ri-notification-3-line"></i>
            <i class="ri-user-line"></i>
        </div>
    </header>

    <div class="app-body">

        {{-- Sidebar élégante --}}
        <aside class="app-sidebar">
            <div class="app-sidebar-header">
                <h2 class="app-sidebar-title">Menu</h2>
            </div>
            <nav class="app-sidebar-nav">
                @auth
                    @if(auth()->user()->role === 'administrateur')
                        @include('layout.sidebar1')
                    @elseif(auth()->user()->role === 'livreur')
                        @include('layout.sidebar2')
                    @elseif(auth()->user()->role === 'client')
                        @include('layout.sidebar3') {{-- nouveau fichier pour client --}}
                    @else
                        @include('layout.navbar')
                    @endif
                @endauth
            </nav>
        </aside>

        {{-- Contenu principal --}}
        <main class="app-main">
            @yield('main')
        </main>
    </div>

    {{-- Modales globales --}}
    @yield('modal')

    {{-- Notifications / Toasts --}}
    <div class="app-toasts">
        @yield('toast')
    </div>

</body>
</html>