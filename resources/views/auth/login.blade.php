@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div style="max-width: 400px; margin: 60px auto;">
        <div class="tarjeta">
            <h2 class="text-center mb-lg">Iniciar Sesión</h2>

            <form id="loginForm">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="yesid@example.com" required>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <input type="password" id="password" name="password" value="password123" required>
                </div>

                <button type="submit" class="btn btn-primario" style="width: 100%;" id="submitBtn">
                    Ingresar
                </button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.textContent = 'Ingresando...';

    try {
        const response = await fetch('/api/auth/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                email: document.getElementById('email').value,
                password: document.getElementById('password').value,
            }),
        });

        const json = await response.json();

        if (response.ok) {
            localStorage.setItem('token', json.data.token);
            App.mostrarExito('Login exitoso. Redirigiendo...');
            setTimeout(() => window.location.href = '/libros', 800);
        } else {
            App.mostrarError(json.message || 'Credenciales inválidas');
            btn.disabled = false;
            btn.textContent = 'Ingresar';
        }
    } catch (error) {
        App.mostrarError('Error de conexión: ' + error.message);
        btn.disabled = false;
        btn.textContent = 'Ingresar';
    }
});
</script>
@endpush
