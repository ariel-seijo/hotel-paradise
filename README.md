# Sistema de Reservas para Actividades de Hotel 🏨

Este proyecto es un sistema de reservas desarrollado para gestionar actividades en un hotel, pensado para tres tipos de usuarios finales: Administrador, Recepcionista y Huésped.

## Tecnologías Utilizadas

- **Frontend:**
  - HTML5
  - CSS3
  - JavaScript (vanilla y uso de modales dinámicos)
  - Bootstrap (para el diseño responsivo y componentes interactivos)

- **Backend:**
  - PHP (manejo de lógica del servidor y API REST)
  - MySQL (gestión de la base de datos)

- **Otras herramientas:**
  - PHPMailer (para el envío de correos electrónicos)
  - Git y GitHub (control de versiones)

## Funcionalidades Principales

1. **Login.**
   - Inicio de sesión para usuarios Administrador y Recepcionista.
   - Recuperación de contraseña vía mail.
     
2. **Gestión de Usuarios**  
   - Creación, edición y eliminación de usuarios.  
   - Configuración de permisos.
     
3. **Gestión de Actividades**  
   - Creación, edición y eliminación de actividades.  
   - Configuración de horarios y cupos por actividad.

4. **Reservas de Huéspedes**  
   - Registro de reservas con validación de DNI y datos requeridos.  
   - Confirmación de reserva con envío de correo electrónico.  
   - Gestión de fechas y horarios en tiempo real.  

5. **Panel de Administración**  
   - Listado de actividades y reservas.  
   - Modales dinámicos para agregar o editar actividades.  
   - Control de acceso según rol (administrador o recepcionista).  

6. **Generación de PDF**  
   - Exportación de datos de actividades y horarios seleccionados.  

## Estructura del Proyecto

/PAGINAS turnos.php # Página principal para gestionar turnos y actividades 
/SCRIPT generar-turno.php # Lógica para generar turnos automáticamente 
verificar-dni.php # Verificación de datos de huéspedes 
guardar-reserva.php # Gestión de reservas en la base de datos 
conexion.php # Conexión a la base de datos 

## Configuración e Instalación

### Requisitos Previos
- Servidor web (XAMPP, WAMP o similar).
- PHP 8.0 o superior.
- MySQL 5.7 o superior.

### Instrucciones
1. Clona este repositorio en C:\xampp\htdocs:
   git clone https://github.com/ariel-seijo/hotel-paradise
   
Configura la base de datos importando el archivo paradise bdd.sql incluido en el proyecto.

Edita el archivo conexion.php con tus credenciales de MySQL:

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "paradise";

Accede al sistema desde tu navegador en http://localhost/hotel-paradise/
