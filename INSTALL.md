
# Instrucciones de Instalación del Sistema de Gestión de Telebachillerato

## Requisitos previos

Antes de instalar el sistema, asegúrate de tener instalado:

- **PHP 8.2 o superior**
- **Composer** (gestor de dependencias de PHP)
- **MySQL 5.7 o superior** (o MariaDB)
- **Git** (para clonar el repositorio)
- **Laragon** (recomendado) o XAMPP/WAMP

---

## Pasos para instalar el proyecto

### 1. Clonar el repositorio desde GitHub

```bash
git clone https://github.com/DravenMakenshin/sistema-gestion-telebachillerato.git
cd sistema-gestion-telebachillerato
```

### 2. Instalar dependencias de PHP con Composer

```bash
composer install
```

### 3. Configurar el archivo de entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configurar la base de datos

Editar el archivo `.env` y ajustar las credenciales de tu base de datos:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=gestion_universidad
DB_USERNAME=root
DB_PASSWORD=tu_contraseña_aqui
```

### 5. Crear la base de datos

**Opción A: Usando MySQL Workbench**
```sql
CREATE DATABASE gestion_universidad;
USE gestion_universidad;
```

**Opción B: Usando terminal**
```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS gestion_universidad"
```

### 6. Importar los datos

El archivo de respaldo se encuentra en `database/gestion_universidad.sql`

**Opción A: Usando MySQL Workbench**
- Abrir MySQL Workbench
- Conectar al servidor
- Abrir archivo `database/gestion_universidad.sql`
- Ejecutar el script completo

**Opción B: Usando terminal**
```bash
mysql -u root -p gestion_universidad < database/gestion_universidad.sql
```

**Opción C: Si prefieres importar desde Excel**
```bash
php artisan importar:centros "ruta/CentrosTBC.xlsx"
php artisan importar:alumnos "ruta/AlumnosTBC.xlsx"
```

### 7. Ejecutar migraciones (opcional)

Si la importación no funciona, puedes ejecutar las migraciones:

```bash
php artisan migrate
```

### 8. Iniciar el servidor

```bash
php artisan serve
```

El sistema estará disponible en: `http://127.0.0.1:8000`

---

## Credenciales de acceso

Una vez instalado el sistema, puedes iniciar sesión con:

| Rol | Email | Contraseña |
|-----|-------|------------|
| Administrador | admin@sistema.com | admin123 |
| Consultor | consultor@sistema.com | consultor123 |

### Permisos por rol:

**Administrador**
- ✅ Visualizar centros, alumnos y calificaciones
- ✅ Crear, editar y eliminar centros
- ✅ Crear, editar y eliminar alumnos
- ✅ Capturar y modificar calificaciones
- ✅ Gestionar usuarios (crear, editar, eliminar)
- ✅ Acceso a todas las funciones del sistema

**Consultor**
- ✅ Visualizar centros, alumnos y calificaciones
- ❌ Sin permisos de edición
- ❌ No puede crear, modificar ni eliminar datos
- ❌ No accede a la gestión de usuarios

---

## Estructura de la Base de Datos

| Tabla | Descripción | Registros |
|-------|-------------|-----------|
| centros | Centros educativos | 142 |
| alumnos | Alumnos registrados | 200 |
| materias | Materias por centro | Variable |
| calificaciones | Calificaciones de alumnos | Variable |
| users | Usuarios del sistema | 2 (admin + consultor) |

---

## Solución de problemas comunes

### Error: "Vite manifest not found"

Si aparece este error, ignóralo o ejecuta:

```bash
npm install
npm run build
```

### Error: "Table 'gestion_universidad.sessions' doesn't exist"

Ejecuta:

```bash
php artisan session:table
php artisan migrate
```

### Error: "No se pueden importar fechas"

El sistema maneja automáticamente fechas inválidas, omitiéndolas sin afectar la importación.

### Error 403 al iniciar sesión

Asegúrate de que el usuario tiene el rol correcto:
- Administrador debe tener `rol = admin`
- Consultor debe tener `rol = consultor`

Para corregir:

```bash
php artisan tinker
$user = App\Models\User::where('email', 'admin@sistema.com')->first();
$user->rol = 'admin';
$user->save();
```

---

## Características del Sistema

- ✅ CRUD completo de Centros, Alumnos y Calificaciones
- ✅ Buscador en tiempo real con paginación
- ✅ Captura de calificaciones con AJAX (sin recargar página)
- ✅ Cálculo automático de promedios y estado (Aprobado/Reprobado)
- ✅ Sistema de autenticación con dos roles (Admin/Consultor)
- ✅ Importación de datos desde Excel (centros y alumnos)
- ✅ Interfaz responsiva con Bootstrap 5
- ✅ Reportes de alumnos por centro y calificaciones por alumno

---

## Tecnologías utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| Laravel | 11.x | Framework PHP backend |
| PHP | 8.3 | Lenguaje de programación |
| MySQL | 8.0 | Base de datos |
| Bootstrap | 5.1 | Diseño responsivo |
| jQuery | 3.6 | Peticiones AJAX |
| PHPSpreadsheet | ^5.7 | Importación de Excel |

---

## Contacto

Si tienes problemas con la instalación, revisa la documentación o contacta al desarrollador.

---

