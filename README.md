# Caprichos Pet Shop - Sistema de Gestión y Agendamiento

Instrucciones paso a paso para la instalación, configuración y ejecución del sistema en el entorno de desarrollo local.

---

## Requisitos Previos

Antes de comenzar, asegúrate de tener instaladas las siguientes herramientas en tu equipo:
* **PHP** (Versión 8.2 o superior)
* **Composer** (Gestor de dependencias de PHP)
* **Node.js y NPM**
* **XAMPP** (O un entorno local con Apache y MySQL)
* **Git**

---

## Pasos de Instalación y Configuración

# 1. Clonar el repositorio
Abre tu terminal en la ubicación deseada y clona el proyecto:
```bash
git clone [https://github.com/JuanK96/sistema_web_grooming.git](https://github.com/JuanK96/sistema_web_grooming.git)
cd sistema_web_grooming

# 2. Configurar la Base de Datos
-Inicia el panel de control de XAMPP y activa los servicios de Apache y MySQL.
-Ingresa a phpMyAdmin y crea una base de datos nueva (se recomienda cotejamiento utf8mb4).

# 3. Instalar las dependencias de PHP
Ejecuta el siguiente comando en la raíz del proyecto para descargar los paquetes necesarios mediante Composer:
    composer install

# 4. Configurar el archivo de entorno y UltraMsg
Crea una copia del archivo de configuración base:
    cp .env.example .env

Abre el archivo .env recién creado en tu editor de código (como Visual Studio Code) y configura los siguientes parámetros con tus credenciales locales y las de UltraMsg (para las notificaciones de WhatsApp):
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=nombre_de_tu_base_de_datos
    DB_USERNAME=root
    DB_PASSWORD=tu_contraseña

agregar al env con sus valores
    ULTRAMSG_INSTANCE=
    ULTRAMSG_TOKEN=

# 5. Generar la clave de aplicación y migrar la base de datos
Genera el código de seguridad de Laravel y ejecuta las migraciones para estructurar las tablas:
    php artisan key:generate
    php artisan migrate --seed

# 6. Limpiar caché del framework
Ejecuta los siguientes comandos para asegurar que el sistema reconozca correctamente las clases, rutas y servicios:
    composer dump-autoload
    php artisan config:clear
    php artisan cache:clear

# 7. Iniciar el servidor local
Finalmente, pon en marcha el servidor de desarrollo de Laravel:
    php artisan serve
El sistema estará disponible en tu navegador ingresando a: http://127.0.0.1:8000