@extends('layouts.app')

@section('title', 'Libros')

@section('content')
    <div class="flex-between mb-lg">
        <h2>Listado de Libros</h2>
        <a href="{{ route('prestamos.create') }}" class="btn btn-primario">+ Nuevo Préstamo</a>
    </div>

    <div class="form-group mb-lg">
        <input
            type="text"
            id="searchInput"
            placeholder="Buscar por título..."
            autocomplete="off"
        >
    </div>

    <div id="loading" class="cargando" style="display: none;">Buscando</div>

    <div class="tabla-container">
        <table class="tabla" id="librosTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>ISBN</th>
                    <th>Año</th>
                    <th>Stock</th>
                    <th>Autores</th>
                </tr>
            </thead>
            <tbody id="librosBody">
            </tbody>
        </table>
    </div>

    <div id="emptyState" class="cargando" style="display: none;">
        No se encontraron libros
    </div>

    <div id="pagination" class="flex-between mt-md" style="display: none;">
        <button id="prevPage" class="btn btn-secundario">← Anterior</button>
        <span id="pageInfo"></span>
        <button id="nextPage" class="btn btn-secundario">Siguiente →</button>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/libros.js') }}"></script>
@endpush
