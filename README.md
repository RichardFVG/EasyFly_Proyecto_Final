# Proyecto: EasyFly

---

## Nombre: Richard Francisco Vaca Garcia

## Ciclo: 2do CFGS DAW

## Asignatura: Proyecto de Desarrollo de Aplicaciones Web (PRW)

---

¡Bienvenido/a a **EasyFly**, mi proyecto de fin de ciclo!
EasyFly es una aplicación web MVC desarrollada en **PHP 8** que permite a cualquier usuario registrarse, buscar vuelos, calcular el precio de su reserva con múltiples criterios y confirmar la compra recibiendo un correo electrónico de confirmación. Además, cuenta con un panel de administración donde puedo revisar y gestionar todas las reservas.

---

## Tabla de contenidos

- [Proyecto: EasyFly](#proyecto-easyfly)
  - [Nombre: Richard Francisco Vaca Garcia](#nombre-richard-francisco-vaca-garcia)
  - [Ciclo: 2do CFGS DAW](#ciclo-2do-cfgs-daw)
  - [Asignatura: Proyecto de Desarrollo de Aplicaciones Web (PRW)](#asignatura-proyecto-de-desarrollo-de-aplicaciones-web-prw)
  - [Tabla de contenidos](#tabla-de-contenidos)
  - [Demo rápida](#demo-rápida)
  - [Motivación y objetivos](#motivación-y-objetivos)
  - [Tecnologías](#tecnologías)
  - [Requisitos previos](#requisitos-previos)
  - [Instalación paso a paso](#instalación-paso-a-paso)
  - [Configuración](#configuración)
  - [Estructura del proyecto](#estructura-del-proyecto)
  - [Flujo de la aplicación](#flujo-de-la-aplicación)
  - [Seguridad](#seguridad)
  - [Posibles mejoras](#posibles-mejoras)
  - [Autor y licencia](#autor-y-licencia)

---

## Demo rápida

| Rol               | Credenciales                       | Qué probar                                        |
| ----------------- | ---------------------------------- | ------------------------------------------------- |
| **Administrador** | `richi3fvg@gmail.com` o Richard / `admin123` | Acceso al panel **Admin** → cancelar reservas     |
| **Usuario**       | `the4lpha0ne@gmail.com` o Administrador / `1234`   | Reservar un vuelo y ver la confirmación por email |

> **Nota:** Los datos de autenticación están precargados en *database.sql*. Todas las contraseñas están hasheadas con **bcrypt**.

---

## Motivación y objetivos

* **Aprender** a montar un patrón MVC ligero en PHP sin frameworks pesados.
* **Practicar** el uso de Composer y la integración de APIs externas (Mailjet y OpenWeather).
* **Diseñar** una base de datos relacional coherente con claves foráneas y transacciones.
* **Aplicar** buenas prácticas en validación, sanitización y manejo de sesiones.
* **Ofrecer** una experiencia de reserva lo más intuitiva posible.

---

## Tecnologías

| Categoría | Herramientas                            |
| --------- | --------------------------------------- |
| Back-End  | PHP 8.2 · PDO/MySQL · Composer          |
| Front-End | HTML 5 · CSS 3 · Bootstrap 5            |
| APIs      | Mailjet (e-mails) · OpenWeather (clima) |
| Otros     | XAMPP · cURL · JavaScript vanilla       |

---

## Requisitos previos

1. **PHP ≥ 8.1** con extensiones `pdo_mysql` y `curl` habilitadas.
2. **MySQL 8** (o MariaDB compatible).
3. **Composer** para la instalación de dependencias.
4. Un servidor local tipo **XAMPP**, **MAMP** o equivalente.

---

## Instalación paso a paso

1. **Clonar** o descargar este repositorio en `C:\xampp\htdocs\public_html` (o la ruta de tu servidor).

2. Ejecutar:

   ```bash
   cd public_html
   composer install
   ```

3. Importar el esquema y datos de prueba **(Ignorar este paso si se está utilizando XAMPP, en ese caso se puede importar la base de datos directamente a phpmyadmin)**:

   ```bash
   mysql -u root -p < database.sql
   ```

4. Verificar que *config/config.php* contenga tus credenciales de MySQL:

   ```php
   define('DB_USER', 'root');
   define('DB_PASS', 'tu_contraseña');
   ```

5. Las claves reales de Mailjet y ~~OpenWeather~~ ya están incluídas en el código (config.php)

6. Levantar Apache y MySQL desde XAMPP y navegar a **[http://localhost/public\_html/default.php](http://localhost/public_html/default.php)**.

---

## Configuración

Edita **config/config.php**:

```php
// Base de datos
define('DB_HOST', 'localhost');
define('DB_NAME', 'easyfly');
define('DB_USER', 'root');
define('DB_PASS', '');

// APIs
define('MAILJET_API_PUBLIC',  'EL_API_KEY');
define('MAILJET_API_PRIVATE', 'EL_API_SECRET');
define('OPENWEATHER_KEY',     'EL_API_KEY');
```

> Si no introduces claves válidas, los correos de confirmación no se enviarán y el banner del clima no aparecerá.

---

## Estructura del proyecto

```
public_html/
├── assets/            # CSS, imágenes y JS de front-end
├── config/            # Configuración y conexión PDO
├── controllers/       # Lógica de negocio por sección (MVC)
├── helpers/           # Clases utilitarias (Auth, Mail, Weather)
├── models/            # Acceso a datos (FlightModel, etc.)
├── views/             # Vistas PHP + Bootstrap
├── vendor/            # Librerías instaladas con Composer
├── database.sql       # Script de creación y datos de ejemplo
└── default.php        # Front-controller (routing sencillo)
```

---

## Flujo de la aplicación

1. **Home** – bienvenida y destinos destacados.
2. **Registro / Login** – autenticación segura con `password_hash()`.
3. **Flights** – formulario integral: origen, destino, fecha, tipo de pasajero, equipaje, clase y mascota.
4. **Confirm Flight** – resumen detallado + cálculo de precio en tiempo real.
5. **Reserva** – inserción transaccional en MySQL, decremento de plazas y envío de e-mail.
6. **Perfil** – historial de reservas y opción a cancelarlas.
7. **Admin** – listado global con filtro para eliminar cualquier reserva.

---

## Seguridad

* **Sesiones** protegidas y regeneradas al iniciar sesión.
* **Autorización**: rutas diferenciadas para usuario normal y admin.
* **Validación+Sanitización** de todos los datos de entrada.
* **Transacciones** al crear y borrar reservas para mantener la integridad de plazas.
* **Password hashing** con Bcrypt (`PASSWORD_BCRYPT`).

---

## Posibles mejoras

* Pasarelas de pago reales (Stripe, PayPal).
* Buscador de vuelos multi-origen y multi-escala.
* Subida de imagen del billete y QR.
* Internationalization (i18n) completo.
* Tests unitarios con PHPUnit.
* Dockerización y despliegue en un VPS.

---

## Autor y licencia

Creado por **Richard Francisco Vaca Garcia**.
Este proyecto se distribuye bajo la licencia MIT.
