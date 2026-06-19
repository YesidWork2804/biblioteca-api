const Libros = {
    searchInput: null,
    tableBody: null,
    loading: null,
    emptyState: null,
    pagination: null,
    prevBtn: null,
    nextBtn: null,
    pageInfo: null,
    currentPage: 1,
    lastPage: 1,
    debounceTimer: null,

    init() {
        this.searchInput = document.getElementById('searchInput');
        this.tableBody = document.getElementById('librosBody');
        this.loading = document.getElementById('loading');
        this.emptyState = document.getElementById('emptyState');
        this.pagination = document.getElementById('pagination');
        this.prevBtn = document.getElementById('prevPage');
        this.nextBtn = document.getElementById('nextPage');
        this.pageInfo = document.getElementById('pageInfo');

        if (!this.searchInput) return;

        this.searchInput.addEventListener('input', () => {
            clearTimeout(this.debounceTimer);
            this.debounceTimer = setTimeout(() => {
                this.currentPage = 1;
                this.cargarLibros();
            }, 300);
        });

        this.prevBtn.addEventListener('click', () => {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.cargarLibros();
            }
        });

        this.nextBtn.addEventListener('click', () => {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.cargarLibros();
            }
        });

        this.cargarLibros();
    },

    async cargarLibros() {
        const query = this.searchInput.value.trim();
        this.loading.style.display = 'block';
        this.tableBody.innerHTML = '';
        this.emptyState.style.display = 'none';
        this.pagination.style.display = 'none';

        try {
            const url = `/api/libros?titulo=${encodeURIComponent(query)}&page=${this.currentPage}`;
            const response = await App.fetch(url);

            if (!response.ok) {
                throw new Error('Error al cargar libros');
            }

            const json = await response.json();
            this.renderLibros(json.data);
            this.renderPaginacion(json.meta);
        } catch (error) {
            App.mostrarError(error.message);
        } finally {
            this.loading.style.display = 'none';
        }
    },

    renderLibros(libros) {
        if (!libros || libros.length === 0) {
            this.emptyState.style.display = 'block';
            return;
        }

        this.tableBody.innerHTML = libros.map(libro => `
            <tr>
                <td>${libro.id}</td>
                <td>${this.escapeHtml(libro.titulo)}</td>
                <td>${this.escapeHtml(libro.isbn)}</td>
                <td>${libro.anio_publicacion}</td>
                <td>
                    <span style="color: ${libro.stock_disponible > 0 ? 'var(--color-exito)' : 'var(--color-error)'}; font-weight: 600;">
                        ${libro.stock_disponible}
                    </span>
                </td>
                <td>${this.renderAutores(libro.autores)}</td>
            </tr>
        `).join('');
    },

    renderAutores(autores) {
        if (!autores || autores.length === 0) return '—';
        return autores.map(a => this.escapeHtml(a.nombre_completo)).join(', ');
    },

    renderPaginacion(meta) {
        if (!meta || meta.last_page <= 1) {
            this.pagination.style.display = 'none';
            return;
        }

        this.currentPage = meta.current_page;
        this.lastPage = meta.last_page;
        this.pageInfo.textContent = `Página ${this.currentPage} de ${this.lastPage} (${meta.total} libros)`;
        this.prevBtn.disabled = this.currentPage <= 1;
        this.nextBtn.disabled = this.currentPage >= this.lastPage;
        this.pagination.style.display = 'flex';
    },

    escapeHtml(text) {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
};

document.addEventListener('DOMContentLoaded', () => Libros.init());
