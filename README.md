# Sistema de Gestión para Telebachillerato

Sistema completo para la gestión de centros educativos, alumnos y calificaciones. Desarrollado con Laravel 11 como parte de una evaluación práctica.

## 📋 Características principales

- ✅ CRUD completo de Centros, Alumnos y Calificaciones
- ✅ Buscador en tiempo real con paginación
- ✅ Captura de calificaciones con AJAX (sin recargar página)
- ✅ Cálculo automático de promedios y estado (Aprobado/Reprobado)
- ✅ Sistema de autenticación con dos roles (Admin/Consultor)
- ✅ Importación de datos desde Excel (centros y alumnos)
- ✅ Interfaz responsiva con Bootstrap 5
- ✅ API JSON para consulta de datos

## 🛠️ Tecnologías utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| Laravel | 11.x | Framework PHP backend |
| PHP | 8.3 | Lenguaje de programación |
| MySQL | 8.0 | Base de datos |
| Bootstrap | 5.1 | Diseño responsivo |
| jQuery | 3.6 | Peticiones AJAX |
| PHPSpreadsheet | ^5.7 | Importación de Excel |

## 📊 Base de Datos

| Tabla | Descripción | Registros |
|-------|-------------|-----------|
| `centros` | Centros educativos | 142 |
| `alumnos` | Alumnos registrados | 200 |
| `materias` | Materias por centro | Variable |
| `calificaciones` | Calificaciones de alumnos | Variable |
| `users` | Usuarios del sistema | 2 (admin + consultor) |

## 🔑 Credenciales de acceso

| Rol | Email | Contraseña |
|-----|-------|------------|
| **Administrador** | `admin@sistema.com` | `admin123` |
| **Consultor** | `consultor@sistema.com` | `consultor123` |

### Permisos por rol:

| Acción | Admin | Consultor |
|--------|-------|-----------|
| Ver centros, alumnos, calificaciones | ✅ | ✅ |
| Crear, editar, eliminar centros | ✅ | ❌ |
| Crear, editar, eliminar alumnos | ✅ | ❌ |
| Capturar y modificar calificaciones | ✅ | ❌ |
| Gestionar usuarios | ✅ | ❌ |

## 📸 Capturas de pantalla

> Las capturas de pantalla se encuentran en la carpeta `screenshots/`

## 📥 Instalación

Para instrucciones detalladas de instalación, consulta el archivo [INSTALL.md](INSTALL.md)

### Instalación rápida:

```bash
# Clonar repositorio
git clone https://github.com/DravenMakenshin/sistema-gestion-telebachillerato.git
cd sistema-gestion-telebachillerato

# Instalar dependencias
composer install
cp .env.example .env
php artisan key:generate

# Configurar base de datos en .env
# Luego importar BD o ejecutar migraciones

# Iniciar servidor
php artisan serve

📁 Estructura del proyecto
sistema-gestion-telebachillerato/
├── app/
│   ├── Console/Commands/     # Comandos importar Excel
│   ├── Http/Controllers/     # Controladores
│   ├── Http/Middleware/      # Middlewares roles
│   └── Models/               # Modelos
├── database/
│   └── gestion_universidad.sql  # Respaldo BD
├── resources/views/
│   ├── layouts/
│   ├── centros/
│   ├── alumnos/
│   ├── calificaciones/
│   └── usuarios/
├── routes/
│   └── web.php
├── screenshots/              # Capturas de pantalla
├── INSTALL.md                # Instrucciones detalladas
└── README.md                 # Este archivo

🐛 Solución de problemas comunes
Problema	Solución
Vite manifest error	Ignorar, usar CDN Bootstrap
Tabla sessions no existe	php artisan session:table && php artisan migrate
Error 403 al iniciar sesión	Verificar rol del usuario en BD
Fechas inválidas al importar	El sistema las omite automáticamente

📧 Contacto
Desarrollado para evaluación práctica de desarrollador.

© 2024 - Sistema de Gestión de Telebachillerato