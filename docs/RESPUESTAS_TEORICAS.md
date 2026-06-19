# Respuestas a Preguntas Teóricas

## 1. Diferencia entre `hasMany()` y `belongsToMany()` en Eloquent

### `hasMany()`
Define una relación **uno a muchos**. Un registro padre tiene muchos registros hijos, donde el hijo tiene una clave foránea que apunta al padre.

**Ejemplo:** Un Autor tiene muchos Libros (cada libro tiene un `autor_id` que apunta al autor).

```php
// Modelo Autor
public function libros(): HasMany
{
    return $this->hasMany(Libro::class);
}

// Uso
$autor = Autor::find(1);
$libros = $autor->libros; // Todos los libros del autor
```

**Estructura SQL:**
- `autores` (id, nombre, ...)
- `libros` (id, titulo, autor_id, ...)

### `belongsToMany()`
Define una relación **muchos a muchos** que requiere una **tabla pivot** intermedia. Un registro A puede tener muchos registros B, y viceversa.

**Ejemplo:** Un Libro puede tener muchos Autores, y un Autor puede escribir muchos Libros. Se necesita una tabla `autor_libro` con `autor_id` y `libro_id`.

```php
// Modelo Libro
public function autores(): BelongsToMany
{
    return $this->belongsToMany(Autor::class, 'autor_libro')
        ->withPivot('orden_autor')
        ->withTimestamps();
}

// Uso
$libro = Libro::find(1);
$autores = $libro->autores; // Todos los autores del libro
```

**Estructura SQL:**
- `autores` (id, nombre, ...)
- `libros` (id, titulo, ...)
- `autor_libro` (id, autor_id, libro_id, orden_autor) ← pivot

### Cuándo usar cada uno
- **hasMany:** cuando solo hay una clave foránea en la tabla hija apuntando al padre
- **belongsToMany:** cuando ambos lados pueden tener múltiples instancias del otro (requiere tabla pivot)

---

## 2. Diferencia entre `all()`, `get()` y `find()`

### `all()`
Trae **TODOS** los registros de la tabla sin filtrar. No permite encadenar condiciones. Equivale a `SELECT * FROM tabla`.

```php
$libros = Libro::all();
```

**Problema:** No puedes agregar `where`, `orderBy`, etc. después. Poco flexible.

### `get()`
Ejecuta la consulta construida con el query builder. **Permite encadenar condiciones**.

```php
$libros = Libro::where('stock_disponible', '>', 0)
    ->orderBy('titulo')
    ->get();
```

Es el método final que materializa los resultados.

### `find()`
Busca un registro por su **clave primaria** (id). Devuelve un solo modelo o `null`.

```php
$libro = Libro::find(5); // Busca WHERE id = 5
```

Equivale a `Libro::where('id', 5)->first()`.

### Cuándo usar cada uno
- **`all()`:** Solo para tablas pequeñas donde realmente quieres todo (ej: países, estados). Evitar en producción.
- **`get()`:** Cuando necesitas filtros, orden, joins o cualquier lógica de consulta.
- **`find($id)`:** Cuando conoces el ID y esperas un único registro.

---

## 3. Patrón Repository en Laravel

### Qué es
Es un patrón que **separa la lógica de acceso a datos** del resto de la aplicación. Creas una **interfaz** (contrato) y una **implementación** que encapsula todas las consultas a la base de datos.

### Estructura
```php
// Interfaz (contrato)
interface LibroRepositoryInterface
{
    public function paginate(array $filters);
    public function find(int $id);
    public function create(array $data);
}

// Implementación
class EloquentLibroRepository implements LibroRepositoryInterface
{
    public function paginate(array $filters) { /* ... */ }
    public function find(int $id) { return Libro::find($id); }
    public function create(array $data) { return Libro::create($data); }
}

// Controller (no sabe de Eloquent)
class LibroController extends Controller
{
    public function __construct(
        private LibroRepositoryInterface $libroRepository
    ) {}
}
```

### Cuándo implementarlo
- **Aplicaciones medianas/grandes** con lógica de negocio compleja
- Cuando necesitas **cambiar de ORM** sin tocar los controllers
- Para **facilitar pruebas unitarias** (puedes hacer mock del repositorio)
- En proyectos con **múltiples desarrolladores** donde quieres separar responsabilidades

### Cuándo NO implementarlo
- Proyectos pequeños o CRUDs simples
- Cuando añade más complejidad que beneficio
- Prototipos rápidos

### Beneficios
- **Testabilidad:** Puedes mockear el repositorio en tests
- **Mantenibilidad:** Cambias la implementación sin tocar controllers
- **Claridad:** Cada repositorio encapsula una entidad

---

## 4. N+1 Problem en Eloquent

### Qué es
Es un problema de **rendimiento** donde se ejecuta 1 consulta inicial + N consultas adicionales (una por cada resultado). Ocurre cuando accedes a relaciones dentro de un loop sin cargarlas previamente.

### Ejemplo del problema
```php
$libros = Libro::all(); // 1 consulta: SELECT * FROM libros

foreach ($libros as $libro) {
    echo $libro->autores->nombre; // N consultas: SELECT * FROM autores WHERE id = ?
}
// Total: 1 + 20 = 21 consultas
```

### Solución: Eager Loading con `with()`
```php
$libros = Libro::with('autores')->get(); // 2 consultas totales

foreach ($libros as $libro) {
    echo $libro->autores->nombre; // Ya están cargados, sin consultas extras
}
```

Solo se ejecutan 2 consultas:
1. `SELECT * FROM libros`
2. `SELECT * FROM autores WHERE libro_id IN (1, 2, 3, ...)`

### Variantes
- **Carga anidada:** `Libro::with('autores.libros')->get()`
- **Restricciones:** `Libro::with(['autores' => fn($q) => $q->where('nacionalidad', 'Colombiana')])->get()`
- **Lazy eager loading:** `$libros->load('autores')` cuando ya tienes la colección

### Cómo detectarlo
Activa el log de queries con `DB::enableQueryLog()` y observa cuántas se ejecutan.

---

## 5. Autenticación vs Autorización en APIs

### Autenticación (Authentication)
**Verificar quién eres.** Es el proceso de validar la identidad del usuario.

**Ejemplo:** El usuario envía email y password, el sistema valida que sean correctos y devuelve un token (Sanctum, JWT, etc.).

```php
// Verificar credenciales
if (Auth::attempt(['email' => $email, 'password' => $password])) {
    $token = $user->createToken('auth')->plainTextToken;
    return response()->json(['token' => $token]);
}
```

### Autorización (Authorization)
**Verificar qué puedes hacer.** Es el proceso de validar si un usuario autenticado tiene permiso para realizar una acción.

**Ejemplo:** Un usuario puede ver libros pero no eliminarlos. O solo un admin puede crear préstamos.

```php
// Middleware de Laravel
Route::delete('/libros/{id}', [LibroController::class, 'destroy'])
    ->middleware('can:delete,libro');

// Gates
Gate::define('delete-libro', fn($user) => $user->isAdmin());

// Policies
class LibroPolicy
{
    public function delete(User $user, Libro $libro): bool
    {
        return $user->isAdmin();
    }
}
```

### Diferencia clave
| | Autenticación | Autorización |
|---|---|---|
| **Pregunta** | ¿Quién eres? | ¿Qué puedes hacer? |
| **Cuándo** | Primero | Después de autenticar |
| **Ejemplo** | Login con email/password | Verificar rol o permisos |
| **En Laravel** | Sanctum, Passport, sesión | Gates, Policies, middleware `can` |

### En esta prueba
- **Autenticación:** Sanctum con tokens (`Authorization: Bearer ...`)
- **Autorización:** Middleware `auth:sanctum` en rutas protegidas

---

## 6. Diferencia entre PATCH y PUT en APIs REST

### PATCH
Aplica **modificaciones parciales**. Solo envías los campos que quieres cambiar. Los demás se mantienen igual.

```http
PATCH /api/libros/1
Content-Type: application/json

{
  "stock_disponible": 10
}
```

Solo actualiza el stock. El título, ISBN, etc. quedan intactos.

### PUT
Realiza una **actualización completa** del recurso. Debes enviar **todos** los campos. Los que no envías se reemplazan con `null` o valores por defecto.

```http
PUT /api/libros/1
Content-Type: application/json

{
  "titulo": "Nuevo título",
  "isbn": "978-1234567890",
  "anio_publicacion": 2024,
  "numero_paginas": 300,
  "descripcion": "...",
  "stock_disponible": 10,
  "autores": [1, 2]
}
```

### Diferencia práctica

| Aspecto | PATCH | PUT |
|---------|-------|-----|
| **Modificación** | Parcial | Completa |
| **Campos requeridos** | Solo los que cambias | Todos |
| **Idempotencia** | Sí (mismo resultado) | Sí (mismo resultado) |
| **Uso común** | Cambiar 1-2 campos | Reemplazar todo |

### Cuándo usar cada uno
- **PATCH:** Cuando solo cambias un campo (ej: actualizar solo el stock)
- **PUT:** Cuando reemplazas el recurso completo (ej: formulario de edición con todos los campos)

### En esta API
Ambos apuntan al mismo método `update` con `sometimes` en las validaciones, así funcionan idénticamente. Pero conceptualmente:
- `PATCH /api/libros/1` con `{"stock_disponible": 10}` → solo cambia stock
- `PUT /api/libros/1` con el body completo → actualiza todo
