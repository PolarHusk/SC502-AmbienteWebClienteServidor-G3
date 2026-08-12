# SC502-AmbienteWebClienteServidor-G3

# Manual de instalación / Installation Manual

---

# Español

## 1. Requisitos

Para ejecutar el proyecto correctamente, se necesita instalar:

- XAMPP.
- Apache.
- MySQL.
- MySQL Workbench.
- Un navegador web.
- El proyecto web dentro de la carpeta `htdocs`.

## 2. Crear la base de datos

Abrir MySQL Workbench y conectarse al servidor local.

Crear la base de datos requerida por el proyecto ejecutando el script SQL "schemedb.sql" en /

## 3. Copiar el proyecto en htdocs

Ubicar la carpeta de instalación de XAMPP.

En Windows, normalmente se encuentra en:

```text
C:\xampp\htdocs
```

Copiar la carpeta completa del proyecto dentro de `htdocs`.



## 4. Configurar la conexión a MySQL

Buscar el archivo encargado de realizar la conexión con la base de datos en app/config/config.php

La configuración debería utilizar los datos del servidor local.

Ejemplo:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "tienda_geek";
```

Verificar que el nombre de la base de datos coincida con la creada en MySQL Workbench.

## 5. Ejecutar el proyecto

Verificar que Apache y MySQL estén activos en XAMPP y ejecutar el archivo en 

http://localhost/SC502-AmbienteWebClienteServidor-G3/public/index.php
