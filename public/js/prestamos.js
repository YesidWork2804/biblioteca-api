const Prestamos = {
    form: null,
    usuarioSelect: null,
    libroSelect: null,
    fechaPrestamo: null,
    fechaDevolucion: null,
    submitBtn: null,

    init() {
        this.form = document.getElementById('prestamoForm');
        this.usuarioSelect = document.getElementById('usuario_id');
        this.libroSelect = document.getElementById('libro_id');
        this.fechaPrestamo = document.getElementById('fecha_prestamo');
        this.fechaDevolucion = document.getElementById('fecha_devolucion_estimada');
        this.submitBtn = document.getElementById('submitBtn');

        if (!this.form) return;

        this.establecerFechasDefecto();
        this.cargarUsuarios();
        this.cargarLibros();

        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.enviarFormulario();
        });

        this.limpiarErroresEnCambio();
    },

    establecerFechasDefecto() {
        const hoy = new Date();
        const en15Dias = new Date();
        en15Dias.setDate(hoy.getDate() + 15);

        this.fechaPrestamo.value = hoy.toISOString().split('T')[0];
        this.fechaDevolucion.value = en15Dias.toISOString().split('T')[0];
        this.fechaPrestamo.min = hoy.toISOString().split('T')[0];
    },

    async cargarUsuarios() {
        try {
            const response = await App.fetch('/api/usuarios');
            if (!response.ok) throw new Error('Error al cargar usuarios');

            const json = await response.json();
            this.usuarioSelect.innerHTML = '<option value="">Seleccione un usuario</option>';
            json.data.forEach(usuario => {
                if (!usuario.estado) return;
                const option = document.createElement('option');
                option.value = usuario.id;
                option.textContent = usuario.nombre_completo;
                this.usuarioSelect.appendChild(option);
            });
        } catch (error) {
            this.usuarioSelect.innerHTML = '<option value="">Error al cargar usuarios</option>';
            App.mostrarError(error.message);
        }
    },

    async cargarLibros() {
        try {
            const response = await App.fetch('/api/libros?disponibles=1');
            if (!response.ok) throw new Error('Error al cargar libros');

            const json = await response.json();
            this.libroSelect.innerHTML = '<option value="">Seleccione un libro</option>';
            json.data.forEach(libro => {
                const option = document.createElement('option');
                option.value = libro.id;
                option.textContent = `${libro.titulo} (Stock: ${libro.stock_disponible})`;
                this.libroSelect.appendChild(option);
            });
        } catch (error) {
            this.libroSelect.innerHTML = '<option value="">Error al cargar libros</option>';
            App.mostrarError(error.message);
        }
    },

    validar() {
        let valido = true;
        this.limpiarErrores();

        if (!this.usuarioSelect.value) {
            this.mostrarErrorCampo('usuario_id', 'Seleccione un usuario');
            valido = false;
        }

        if (!this.libroSelect.value) {
            this.mostrarErrorCampo('libro_id', 'Seleccione un libro');
            valido = false;
        }

        if (!this.fechaPrestamo.value) {
            this.mostrarErrorCampo('fecha_prestamo', 'La fecha de préstamo es obligatoria');
            valido = false;
        }

        if (!this.fechaDevolucion.value) {
            this.mostrarErrorCampo('fecha_devolucion_estimada', 'La fecha estimada es obligatoria');
            valido = false;
        } else if (this.fechaDevolucion.value < this.fechaPrestamo.value) {
            this.mostrarErrorCampo('fecha_devolucion_estimada', 'Debe ser igual o posterior a la fecha de préstamo');
            valido = false;
        }

        return valido;
    },

    async enviarFormulario() {
        if (!this.validar()) return;

        this.submitBtn.disabled = true;
        this.submitBtn.textContent = 'Enviando...';

        const data = {
            usuario_id: parseInt(this.usuarioSelect.value),
            libro_id: parseInt(this.libroSelect.value),
            fecha_prestamo: this.fechaPrestamo.value,
            fecha_devolucion_estimada: this.fechaDevolucion.value,
        };

        try {
            const response = await App.fetch('/api/prestamos', {
                method: 'POST',
                body: data,
            });

            const json = await response.json();

            if (response.ok) {
                App.mostrarExito('Préstamo creado exitosamente');
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 1500);
            } else if (response.status === 422) {
                if (json.errors) {
                    App.mostrarErrores(json.errors);
                    Object.entries(json.errors).forEach(([campo, mensajes]) => {
                        this.mostrarErrorCampo(campo, mensajes[0]);
                    });
                } else {
                    App.mostrarError(json.message || 'Error de validación');
                }
            } else {
                App.mostrarError(json.message || 'Error al crear el préstamo');
            }
        } catch (error) {
            App.mostrarError('Error de conexión: ' + error.message);
        } finally {
            this.submitBtn.disabled = false;
            this.submitBtn.textContent = 'Crear Préstamo';
        }
    },

    mostrarErrorCampo(campo, mensaje) {
        const el = document.getElementById(`error-${campo}`);
        if (el) el.textContent = mensaje;
    },

    limpiarErrores() {
        document.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
    },

    limpiarErroresEnCambio() {
        [this.usuarioSelect, this.libroSelect, this.fechaPrestamo, this.fechaDevolucion].forEach(el => {
            el.addEventListener('change', () => this.limpiarErrores());
        });
    }
};

document.addEventListener('DOMContentLoaded', () => Prestamos.init());
