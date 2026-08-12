# SC502-AmbienteWebClienteServidor-G3

# Manual de instalación / Installation Manual

---

# Español

## 1. Requisitos

Para ejecutar el proyecto correctamente, se necesita instalar:

- XAMPP
- Apache
- MySQL
- MySQL Workbench
- Un navegador web

Además, el proyecto debe estar ubicado dentro de la carpeta `htdocs` de XAMPP.

## 2. Crear la base de datos

Abrir MySQL Workbench y conectarse al servidor MySQL local.

Crear la base de datos requerida por el proyecto ejecutando el siguiente script SQL:

```text
schemedb.sql
````

Este archivo se encuentra en la raíz del proyecto.

## 3. Copiar el proyecto en htdocs

Ubicar la carpeta de instalación de XAMPP.

En Windows, normalmente se encuentra en:

```text
C:\xampp\htdocs
```

Copiar la carpeta completa del proyecto dentro de `htdocs`.

La ruta debería quedar similar a:

```text
C:\xampp\htdocs\SC502-AmbienteWebClienteServidor-G3
```

## 4. Configurar la conexión a MySQL

Buscar el archivo encargado de realizar la conexión con la base de datos:

```text
app/config/config.php
```

La configuración debe utilizar los datos correspondientes al servidor MySQL local.

Ejemplo:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "sidegeek";
```

Verificar que el nombre de la base de datos, el usuario y la contraseña coincidan con la configuración utilizada en MySQL Workbench.

## 5. Ejecutar el proyecto

Abrir el panel de control de XAMPP y verificar que los servicios de **Apache** y **MySQL** estén activos.

Luego, abrir un navegador web e ingresar a:

```text
http://localhost/SC502-AmbienteWebClienteServidor-G3/public/index.php
```

Si la configuración de Apache, MySQL y la conexión a la base de datos es correcta, la aplicación debería cargar correctamente.

