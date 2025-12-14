# Sistema de Gestión de Turnos - Centro Kinésico (TIF)

## Visión General del Proyecto

Este proyecto fue desarrollado como **Trabajo Integrador Final (TIF)** para la *Tecnicatura Universitaria en Programación (UTN - F.R. Resistencia)*. Su objetivo principal es digitalizar y optimizar la gestión de turnos para un consultorio de kinesiología, ofreciendo una solución robusta y reactiva para administradores, profesionales y pacientes.

---

## Tecnologías Clave (Stack)

| Área | Tecnología | Versión/Detalle |
| :--- | :--- | :--- |
| **Framework** | Laravel | v10/v11 (Moderno) |
| **Frontend** | Livewire 3 + Alpine.js | Interfaz reactiva, minimizando JavaScript manual. |
| **Estilos** | Blade + Tailwind CSS | Desarrollo rápido y consistente de UI. |
| **Base de Datos** | MySQL | Motor relacional robusto. |
| **DevOps** | Git, GitHub, GitHub Actions | Control de versiones y CI/CD para calidad automatizada. |
| **Integraciones** | Brevo (Mailing) | Servicios de mailing transaccional. |

## Arquitectura y Principios de Desarrollo

El sistema se adhiere al patrón **Modelo-Vista-Controlador (MVC)**, incorporando principios modernos de Clean Code y seguridad:

* **Capa de Servicios (Lógica de Negocio):** Se utilizan **Services** (`DisponibilidadService`, `TurnoService`) para encapsular y centralizar las reglas críticas del negocio, como la **prevención del solapamiento de turnos** y la validación horaria.
* **Controladores Reactivos:** La lógica de interfaz es manejada por **Componentes Livewire**, asegurando la reactividad en el *frontend* con un mínimo de código JavaScript.
* **Seguridad y Permisos:**
    * Implementación de **Policies** para gestionar permisos detallados basados en roles (Superadmin, Profesional, Paciente).
    * Uso de **Form Requests** para una validación de entrada de datos centralizada y segura.
* **Integración Continua (CI/CD):** Pipeline configurado en **GitHub Actions** para garantizar la calidad del código. Ejecuta automáticamente `php artisan test` (tests) y controles de estilo (PHPCS) antes de permitir cualquier *merge* a la rama `develop`.
 
---

## Guía de Instalación Local

Sigue estos pasos para poner en marcha el proyecto en tu entorno de desarrollo.

### Prerrequisitos

Asegúrate de tener instalados los siguientes componentes:

* **PHP:** Versión `8.2` o superior.
* **Composer:** Versión `v2`.
* **MySQL Server:** Gestionado a través de herramientas como XAMPP, Laragon, o Docker.
* **Git:** Para clonar el repositorio.

### Pasos de Ejecución

#### 1. Clonar e Instalar Dependencias

```bash
# 1. Clonar el Repositorio
git clone [https://github.com/maibi0996/Gestion_de_turnos.git)
cd Gestion_de_turnos

# 2. Instalar Dependencias de Backend (Composer)
composer install

# 3. Instalar Dependencias de Frontend (NPM)
npm install

# 4. Configurar Entorno
cp .env.example .env

# 5. Generar Clave de Aplicación
php artisan key:generate

# 6. Crear Base de Datos y Ejecutar Migraciones
# Asegúrate de haber creado una base de datos vacía.
php artisan migrate --seed
# (Se recomienda usar --seed para cargar datos iniciales si existen)

Levantar servidores:
En una terminal (Servidor Backend)
php artisan serve
En otra terminal (Servidor Frontend)
npm run dev
El sistema estará accesible en la URL proporcionada por php artisan serve (generalmente http://127.0.0.1:8000).

Equipo de Desarrollo

| Estudiante | Rol Principal | Responsabilidad Clave |
| :--- | :--- | :--- |
| Rodas, Lourdes | Scrum Master / Documentación | Coordinación, Frontend (Blade), Pruebas y QA. |
| Coronel, Gabriel | Developer (Backend) | Lógica de Negocio, Implementación de Livewire. |
| **Medina, Maira** | **Project Manager (PM) / Developer (DB / Backend)** | Diseño y Normalización de la Base de Datos, Desarrollo del Backend, y **Gestión del Proyecto**. |
