@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h2>Dashboard</h2>
    <p class="mb-lg">Resumen general del sistema de biblioteca</p>

    <div class="grid-tarjetas">
        <div class="tarjeta">
            <div class="tarjeta-titulo">Total Libros</div>
            <div class="tarjeta-valor" id="totalLibros">—</div>
        </div>
        <div class="tarjeta tarjeta-exito">
            <div class="tarjeta-titulo">Préstamos Activos</div>
            <div class="tarjeta-valor" id="prestamosActivos">—</div>
        </div>
        <div class="tarjeta tarjeta-error">
            <div class="tarjeta-titulo">Préstamos Vencidos</div>
            <div class="tarjeta-valor" id="prestamosVencidos">—</div>
        </div>
        <div class="tarjeta tarjeta-advertencia">
            <div class="tarjeta-titulo">Libros Sin Stock</div>
            <div class="tarjeta-valor" id="librosSinStock">—</div>
        </div>
    </div>

    <h3>Últimos Préstamos</h3>
    <div id="loadingPrestamos" class="cargando">Cargando</div>
    <table class="tabla" id="ultimosPrestamos" style="display: none;">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Libro</th>
                <th>Fecha Préstamo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="ultimosPrestamosBody"></tbody>
    </table>
@endsection

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
