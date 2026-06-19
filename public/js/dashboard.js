const Dashboard = {
    async init() {
        await Promise.all([
            this.cargarEstadisticas(),
            this.cargarUltimosPrestamos(),
        ]);
    },

    async cargarEstadisticas() {
        try {
            const [librosRes, activosRes, vencidosRes, sinStockRes] = await Promise.all([
                App.fetch('/api/libros'),
                App.fetch('/api/prestamos?estado=activo'),
                App.fetch('/api/prestamos?estado=vencido'),
                App.fetch('/api/libros?disponibles=1'),
            ]);

            if (librosRes.ok) {
                const json = await librosRes.json();
                document.getElementById('totalLibros').textContent = json.meta.total;
            }

            if (activosRes.ok) {
                const json = await activosRes.json();
                document.getElementById('prestamosActivos').textContent = json.meta.total;
            }

            if (vencidosRes.ok) {
                const json = await vencidosRes.json();
                document.getElementById('prestamosVencidos').textContent = json.meta.total;
            }

            if (sinStockRes.ok) {
                const json = await sinStockRes.json();
                const totalLibros = parseInt(document.getElementById('totalLibros').textContent) || 0;
                const disponibles = json.meta.total;
                document.getElementById('librosSinStock').textContent = totalLibros - disponibles;
            }
        } catch (error) {
            App.mostrarError('Error al cargar estadísticas: ' + error.message);
        }
    },

    async cargarUltimosPrestamos() {
        const loading = document.getElementById('loadingPrestamos');
        const tabla = document.getElementById('ultimosPrestamos');
        const tbody = document.getElementById('ultimosPrestamosBody');

        try {
            const response = await App.fetch('/api/prestamos');
            if (!response.ok) throw new Error('Error al cargar préstamos');

            const json = await response.json();
            const ultimos = json.data.slice(0, 5);

            if (ultimos.length === 0) {
                loading.textContent = 'No hay préstamos registrados';
                return;
            }

            tbody.innerHTML = ultimos.map(p => `
                <tr>
                    <td>${p.id}</td>
                    <td>${this.escapeHtml(p.usuario?.nombre_completo || 'N/A')}</td>
                    <td>${this.escapeHtml(p.libro?.titulo || 'N/A')}</td>
                    <td>${p.fecha_prestamo || 'N/A'}</td>
                    <td><span style="color: ${this.colorEstado(p.estado)}; font-weight: 600;">${p.estado}</span></td>
                </tr>
            `).join('');

            loading.style.display = 'none';
            tabla.style.display = 'table';
        } catch (error) {
            loading.textContent = 'Error al cargar préstamos';
            App.mostrarError(error.message);
        }
    },

    colorEstado(estado) {
        switch (estado) {
            case 'activo': return 'var(--color-secundario)';
            case 'devuelto': return 'var(--color-exito)';
            case 'vencido': return 'var(--color-error)';
            default: return 'var(--color-texto)';
        }
    },

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

document.addEventListener('DOMContentLoaded', () => Dashboard.init());
