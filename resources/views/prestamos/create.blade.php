@extends('layouts.app')

@section('title', 'Nuevo Préstamo')

@section('content')
    <div class="flex-between mb-lg">
        <h2>Crear Nuevo Préstamo</h2>
        <a href="{{ route('libros.index') }}" class="btn btn-secundario">← Volver a libros</a>
    </div>

    <form id="prestamoForm" class="tarjeta" style="max-width: 600px;">
        <div class="form-group">
            <label for="usuario_id">Usuario *</label>
            <select id="usuario_id" name="usuario_id" required>
                <option value="">Cargando usuarios...</option>
            </select>
            <div class="error-msg" id="error-usuario_id"></div>
        </div>

        <div class="form-group">
            <label for="libro_id">Libro (solo con stock disponible) *</label>
            <select id="libro_id" name="libro_id" required>
                <option value="">Cargando libros...</option>
            </select>
            <div class="error-msg" id="error-libro_id"></div>
        </div>

        <div class="form-group">
            <label for="fecha_prestamo">Fecha de préstamo *</label>
            <input type="date" id="fecha_prestamo" name="fecha_prestamo" required>
            <div class="error-msg" id="error-fecha_prestamo"></div>
        </div>

        <div class="form-group">
            <label for="fecha_devolucion_estimada">Fecha de devolución estimada *</label>
            <input type="date" id="fecha_devolucion_estimada" name="fecha_devolucion_estimada" required>
            <div class="error-msg" id="error-fecha_devolucion_estimada"></div>
        </div>

        <button type="submit" class="btn btn-primario" id="submitBtn">Crear Préstamo</button>
    </form>
@endsection

@push('scripts')
<script src="{{ asset('js/prestamos.js') }}"></script>
@endpush
