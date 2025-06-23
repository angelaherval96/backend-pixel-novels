# 📖 Pixel Novels - Backend (API)
Este repositorio contiene el backend y la API RESTful para el proyecto Pixel Novels, una plataforma para la distribución de novelas visuales. Desarrollado con Laravel, este servidor se encarga de toda la lógica de negocio, la gestión de la base de datos y la autenticación de usuarios.

## ✨ Características Principales
API RESTful Segura: Endpoints claros y bien estructurados para gestionar todos los recursos.
Autenticación con Laravel Sanctum: Sistema de autenticación basado en tokens para proteger las rutas.
Gestión de Roles y Permisos: Lógica para diferenciar entre usuarios, creadores y administradores.
Operaciones CRUD: Funcionalidad completa para crear, leer, actualizar y eliminar novelas, capítulos, favoritos y más.

## 🛠️ Tecnologías Utilizadas
Framework: Laravel (v10)
Lenguaje: PHP (v8.1+)
Base de Datos: PostgreSQL
Autenticación: Laravel Sanctum
Gestión de Dependencias: Composer

## 🚀 Instalación y Puesta en Marcha
Para ejecutar este servidor en tu entorno local, sigue estos pasos:

### 1. Prerrequisitos
Asegúrate de tener instalado PHP, Composer y PostgreSQL.

### 2. Instalación
   
#### 1. Clona el repositorio
git clone https://github.com/angelaherval96/backend-pixel-novels/
cd nombre-del-repositorio-backend

#### 2. Instala las dependencias
composer install

#### 3. Crea tu archivo de entorno y genera la clave
cp .env.example .env
php artisan key:generate

#### 4. Configura la conexión a tu base de datos en el archivo .env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pixel_novels
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

#### 5. Ejecuta las migraciones para crear la estructura de la base de datos
php artisan migrate

#### 6. Puebla la base de datos con datos de prueba
php artisan db:seed

### 3. Ejecución

#### Inicia el servidor de Laravel
php artisan serve
El servidor estará disponible en http://localhost:8000.

#### Ejemplos de Endpoints de la API

| Método | Endpoint | Descripción | Requiere Auth |
| :--- | :--- | :--- | :--- |
| `GET` | `/api/novels` | Obtiene la lista de todas las novelas. | Sí |
| `GET` | `/api/novels/{id}` | Obtiene los detalles de una novela específica. | Sí|
| `POST` | `/api/register` | Registra un nuevo usuario. | No |
| `POST` | `/api/login` | Inicia sesión y devuelve un token. | No |
| `POST` | `/api/novels/{id}/favourite` | Marca una novela como favorita. | Sí |
| `POST` | `/api/novels` | Crea una nueva novela. | Sí (Rol Creator y Admin)|

📄 Licencia
Este proyecto está bajo la Licencia MIT.
