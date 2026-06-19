<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Biblioteca') - Sistema de Gestión</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header class="app-header">
        <h1>Biblioteca</h1>
        <nav>
            <a href="{{ route('libros.index') }}" class="{{ request()->routeIs('libros.*') ? 'active' : '' }}">Libros</a>
            <a href="{{ route('prestamos.create') }}" class="{{ request()->routeIs('prestamos.*') ? 'active' : '' }}">Nuevo Préstamo</a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a>
        </nav>
    </header>

    <main class="app-container">
        <div id="alert-success" class="alerta alerta-exito"></div>
        <div id="alert-error" class="alerta alerta-error"></div>

        @yield('content')
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
