# Sistema de Gestión de Biblioteca

Aplicación fullstack para administrar una biblioteca: permite registrar autores, gestionar el catálogo de libros con sus existencias, y controlar los préstamos a usuarios aplicando reglas de negocio como límites por usuario, control de stock y detección de vencidos.

Incluye una **API REST** con autenticación por tokens y un **frontend web** con búsqueda en tiempo real, formularios validados y dashboard con estadísticas.

## Características

- :book: Gestión de libros, autores y usuarios
- :handshake: Control de préstamos con reglas de negocio
- :lock: Autenticación con Laravel Sanctum
- :chart_with_upwards_trend: Reportes y estadísticas
- :globe_with_meridians: Frontend con Blade y JavaScript
- :building_blocks: Arquitectura hexagonal (Domain / Application / Infrastructure)

## Stack

- :elephant: PostgreSQL 13+
- :gear: Laravel 11
- :package: Sanctum (autenticación API)
- :clipboard: Postman (pruebas)
- :art: Blade + JavaScript vanilla + SCSS

## Requisitos

- PHP 8.3+
- Composer
- PostgreSQL 13+
- Extensión `mbstring` habilitada

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/YesidWork2804/biblioteca-api.git
cd biblioteca-api

# 2. Instalar dependencias
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configurar la base de datos en .env
# DB_CONNECTION=pgsql
# DB_HOST=127.0.0.1
# DB_PORT=5432
# DB_DATABASE=biblioteca_api
# DB_USERNAME=postgres
# DB_PASSWORD=tu_contraseña

# 5. Crear la base de datos en PostgreSQL
# CREATE DATABASE biblioteca_api;

# 6. Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# 7. Crear usuario para autenticación
php artisan tinker
>>> \App\Models\User::create(['name' => 'Yesid Dev', 'email' => 'yesid@example.com', 'password' => bcrypt('password123')]);

# 8. Levantar el servidor
php artisan serve
```

## Endpoints de la API

### Autenticación
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| POST | `/api/auth/register` | Registrar usuario |
| POST | `/api/auth/login` | Iniciar sesión (devuelve token) |
| POST | `/api/auth/logout` | Cerrar sesión |
| GET | `/api/auth/me` | Datos del usuario autenticado |

### Libros
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/libros` | Listar con paginación y filtros |
| GET | `/api/libros/{id}` | Obtener libro con autores |
| POST | `/api/libros` | Crear libro |
| PUT/PATCH | `/api/libros/{id}` | Actualizar libro |
| DELETE | `/api/libros/{id}` | Eliminar (soft delete) |

Filtros disponibles: `?titulo=...&autor_id=...&anio=...&disponibles=1&page=1`

### Préstamos
| Método | Endpoint | Descripción |
|--------|----------|-------------|
| GET | `/api/prestamos` | Listar con paginación y filtros |
| POST | `/api/prestamos` | Crear préstamo |
| PUT | `/api/prestamos/{id}/devolver` | Marcar como devuelto |
| GET | `/api/usuarios` | Listar usuarios |

Filtros: `?estado=activo|devuelto|vencido&usuario_id=...&libro_id=...`

## Comandos Artisan

```bash
# Marcar préstamos vencidos (más de 15 días)
php artisan prestamos:marcar-vencidos

# Reporte de biblioteca
php artisan reportes:biblioteca
```

El comando `prestamos:marcar-vencidos` se ejecuta **diariamente** según el scheduler configurado en `routes/console.php`.

## Frontend (vistas web)

| Ruta | Descripción |
|------|-------------|
| `/libros` | Listado de libros con búsqueda en tiempo real |
| `/prestamos/crear` | Formulario para crear préstamo |
| `/dashboard` | Estadísticas y últimos préstamos |

Para usar el frontend necesitas un token válido. Haz login en Postman y luego en el navegador (F12 → Console):
```javascript
localStorage.setItem('token', 'TU_TOKEN_AQUI');
```

## Reglas de negocio

1. No se presta un libro si no hay stock disponible
2. Un usuario no puede tener más de 3 préstamos activos
3. No se puede eliminar un autor si tiene libros asociados
4. Los préstamos se marcan como vencidos después de 15 días

## Estructura del proyecto

```
app/
├── Domain/              # Capa de dominio
│   ├── Models/          # Modelos Eloquent
│   ├── Repositories/    # Interfaces (puertos)
│   └── Services/        # Reglas de negocio
├── Infrastructure/      # Capa de infraestructura
│   ├── Http/            # Controllers, Requests, Resources
│   └── Persistence/     # Implementaciones Eloquent
├── Console/Commands/    # Comandos Artisan
└── Http/Controllers/    # Controllers web
```

## Colección de Postman

Importa el archivo `biblioteca-api.postman_collection.json` en Postman. La colección incluye:
- Variables de entorno (`base_url`, `token`)
- Todos los endpoints
- Tests automatizados
- Casos de éxito y error

## Datos de prueba

Después de ejecutar los seeders tendrás:
- 10 autores
- 20 libros con relaciones a autores
- 15 usuarios
- 10 préstamos

**Usuario para login:**
- Email: `yesid@example.com`
- Password: `password123`
- (Debes crearlo con tinker, ver paso 7 de instalación)

## Comandos rápidos de prueba

```bash
# Generar reporte
php artisan reportes:biblioteca

# Marcar préstamos vencidos
php artisan prestamos:marcar-vencidos

# Ver todas las rutas
php artisan route:list
```
