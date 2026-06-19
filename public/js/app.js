const App = {
    token: localStorage.getItem('token'),

    setToken(token) {
        this.token = token;
        localStorage.setItem('token', token);
    },

    clearToken() {
        this.token = null;
        localStorage.removeItem('token');
    },

    async fetch(url, options = {}) {
        const headers = {
            'Accept': 'application/json',
            ...(options.headers || {})
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        if (options.body && typeof options.body === 'object' && !(options.body instanceof FormData)) {
            headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }

        const response = await fetch(url, { ...options, headers });

        if (response.status === 401) {
            this.clearToken();
            this.mostrarError('Sesión expirada. Por favor inicia sesión.');
            setTimeout(() => window.location.href = '/login', 1500);
        }

        return response;
    },

    mostrarExito(mensaje) {
        const alerta = document.getElementById('alert-success');
        alerta.textContent = mensaje;
        alerta.classList.add('visible');
        setTimeout(() => alerta.classList.remove('visible'), 4000);
    },

    mostrarError(mensaje) {
        const alerta = document.getElementById('alert-error');
        alerta.textContent = mensaje;
        alerta.classList.add('visible');
        setTimeout(() => alerta.classList.remove('visible'), 5000);
    },

    mostrarErrores(errores) {
        const mensajes = Object.values(errores).flat().join(', ');
        this.mostrarError(mensajes);
    }
};
