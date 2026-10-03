# Clientes y Solicitudes — CodeIgniter 4

Sistema de gestión desarrollado como práctica de **CodeIgniter 4**, PHP y MySQL. Permite administrar clientes y las solicitudes asociadas mediante una interfaz web y una **API REST que intercambia datos JSON**.

El proyecto utiliza el patrón **MVC** (Model–View–Controller), Entities de CodeIgniter, validación de datos, una relación uno a muchos (1:N) y control de integridad referencial.

> **Alcance:** proyecto educativo, preparado para ejecutarse en un entorno local. Los endpoints de escritura de la API no incorporan autenticación; no debe publicarse directamente en Internet sin implementar los controles de seguridad correspondientes.

## Funcionalidades

- CRUD visual de **Clientes** y **Solicitudes**: alta, listado, detalle, edición y eliminación.
- Relación 1:N: un cliente puede tener varias solicitudes; cada solicitud pertenece a un cliente.
- Impide eliminar clientes que tengan solicitudes asociadas, tanto desde la interfaz HTML como desde la API.
- Estados de solicitud: `Pendiente`, `En proceso` y `Resuelta`.
- Validaciones del lado del servidor, mensajes de confirmación y error, y protección CSRF en los formularios HTML.
- Vistas con layout y hoja de estilos compartidos; indicadores visuales según el estado de cada solicitud.
- Uso de `Cliente` y `Solicitud` como **Entities** para representar registros. El listado visual de solicitudes usa una consulta `JOIN` con un array asociativo para incluir datos del cliente.
- API REST de diez endpoints para consultar, crear, modificar y eliminar clientes y solicitudes mediante JSON.

## Tecnologías utilizadas

| Tecnología | Uso |
| --- | --- |
| PHP 8.4 | Lenguaje del backend (versión usada durante el desarrollo: 8.4.26). |
| CodeIgniter 4 | Framework MVC (versión usada: 4.7.4). |
| Composer | Gestión de dependencias. |
| Apache 2.4 | Servidor HTTP con VirtualHost local. |
| MySQL 8 | Base de datos relacional. |
| HTML, CSS y PHP Views | Interfaz web. |
| Git y GitHub | Control de versiones. |

El entorno de práctica fue configurado **de forma nativa en Windows, sin XAMPP ni Docker**.

## Estructura principal

```text
app/
├── Config/
│   └── Routes.php
├── Controllers/
│   ├── Api/
│   │   ├── Clientes.php
│   │   └── Solicitudes.php
│   ├── Clientes.php
│   └── Solicitudes.php
├── Entities/
│   ├── Cliente.php
│   └── Solicitud.php
├── Models/
│   ├── ClienteModel.php
│   └── SolicitudModel.php
└── Views/
    ├── clientes/
    ├── solicitudes/
    └── layouts/
        └── main.php
public/
└── assets/
    └── css/
        └── app.css
```

Los Controllers de la raíz de `app/Controllers` atienden la interfaz HTML. Los de `app/Controllers/Api` atienden las peticiones de la API REST y producen respuestas JSON. Ambos reutilizan los Models y las Entities.

## Instalación y ejecución local

### 1. Requisitos previos

Disponer de **PHP**, **Composer**, **Apache**, **MySQL** y **Git** instalados. Para la conexión con MySQL, verificar que PHP tenga habilitadas las extensiones necesarias, particularmente `intl`, `mbstring`, `mysqli` y `pdo_mysql`.

Comprobar las herramientas desde una terminal:

```powershell
php -v
composer --version
git --version
```

### 2. Clonar el repositorio e instalar dependencias

```powershell
git clone https://github.com/andresoria2295/Clientes-Solicitudes-CI4.git
cd Clientes-Solicitudes-CI4
composer install
```

> La carpeta `vendor/` no se versiona: Composer la reconstruye a partir de los archivos de dependencias del proyecto.

### 3. Crear la base de datos

En MySQL Workbench o desde el cliente de MySQL, ejecutar el siguiente esquema:

```sql
CREATE DATABASE IF NOT EXISTS practica_codeigniter
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE practica_codeigniter;

CREATE TABLE clientes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(100),
    email VARCHAR(190) NOT NULL UNIQUE,
    telefono VARCHAR(30)
);

CREATE TABLE solicitudes (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    cliente_id INT UNSIGNED NOT NULL,
    asunto VARCHAR(150) NOT NULL,
    descripcion TEXT NOT NULL,
    estado ENUM('Pendiente', 'En proceso', 'Resuelta')
        NOT NULL DEFAULT 'Pendiente',
    INDEX idx_solicitudes_cliente (cliente_id),
    CONSTRAINT fk_solicitudes_cliente
        FOREIGN KEY (cliente_id) REFERENCES clientes(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);
```

**Relación:** `solicitudes.cliente_id` referencia `clientes.id`. La restricción `ON DELETE RESTRICT` evita eliminar un cliente mientras existan solicitudes asociadas.

Crear un usuario de MySQL con permisos apropiados sobre la base de datos o utilizar un usuario local ya configurado. En el entorno de desarrollo se utilizó `ci_clientes` como nombre de usuario; su contraseña **no forma parte del repositorio**.

### 4. Configurar el archivo `.env`

Copiar la plantilla `env` incluida con CodeIgniter:

```powershell
Copy-Item env .env
```

Editar `.env` con los datos correspondientes a la instalación local. Ejemplo basado en el entorno utilizado durante el desarrollo:

```ini
CI_ENVIRONMENT = development

app.baseURL = 'http://clientes-solicitudes.test/'

database.default.hostname = 127.0.0.1
database.default.database = practica_codeigniter
database.default.username = ci_clientes
database.default.password = COLOCAR_CONTRASENA_LOCAL
database.default.DBDriver = MySQLi
database.default.port = 3307
```

**Importante:** el puerto `3307` corresponde al entorno de esta práctica. Si MySQL utiliza el puerto habitual, ajustarlo a `3306`. No subir `.env` a GitHub: puede contener credenciales.

### 5. Configurar Apache y el dominio local

El `DocumentRoot` debe apuntar a la carpeta **`public/`** del proyecto, no a su raíz. Ejemplo orientativo de VirtualHost (reemplazar la ruta por la ubicación real):

```apache
<VirtualHost *:80>
    ServerName clientes-solicitudes.test
    DocumentRoot "C:/ruta/a/Clientes-Solicitudes-CI4/public"

    <Directory "C:/ruta/a/Clientes-Solicitudes-CI4/public">
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Verificar que Apache tenga cargado PHP y habilitado `mod_rewrite`, y que el archivo de configuración incluya los VirtualHosts.

En Windows, editar como administrador:

```text
C:\Windows\System32\drivers\etc\hosts
```

Agregar:

```text
127.0.0.1 clientes-solicitudes.test
```

Reiniciar Apache y acceder a:

- Interfaz de Clientes: `http://clientes-solicitudes.test/clientes`
- Interfaz de Solicitudes: `http://clientes-solicitudes.test/solicitudes`

Para verificar las rutas registradas en CodeIgniter:

```powershell
php spark routes
```

## API REST

La API devuelve JSON. Las operaciones de creación y actualización reciben un cuerpo JSON con el encabezado `Content-Type: application/json`.

### Endpoints de Clientes

| Método | Ruta | Descripción |
| --- | --- | --- |
| `GET` | `/api/clientes` | Listar clientes. |
| `GET` | `/api/clientes/{id}` | Obtener un cliente por ID. |
| `POST` | `/api/clientes` | Crear un cliente. |
| `PUT` | `/api/clientes/{id}` | Actualizar un cliente. |
| `DELETE` | `/api/clientes/{id}` | Eliminar un cliente si no tiene solicitudes. |

Ejemplo del cuerpo JSON para `POST` y `PUT` de Clientes:

```json
{
  "nombre": "Ana",
  "apellido": "Pérez",
  "email": "ana@example.com",
  "telefono": "2611234567"
}
```

### Endpoints de Solicitudes

| Método | Ruta | Descripción |
| --- | --- | --- |
| `GET` | `/api/solicitudes` | Listar solicitudes. |
| `GET` | `/api/solicitudes/{id}` | Obtener una solicitud por ID. |
| `POST` | `/api/solicitudes` | Crear una solicitud vinculada a un cliente existente. |
| `PUT` | `/api/solicitudes/{id}` | Actualizar una solicitud. |
| `DELETE` | `/api/solicitudes/{id}` | Eliminar una solicitud sin eliminar su cliente. |

Ejemplo del cuerpo JSON para `POST` y `PUT` de Solicitudes:

```json
{
  "cliente_id": 1,
  "asunto": "Problema de acceso",
  "descripcion": "No puedo ingresar al sistema.",
  "estado": "Pendiente"
}
```

`cliente_id` debe identificar a un cliente existente. Los estados admitidos son exactamente `Pendiente`, `En proceso` y `Resuelta`.

### Códigos de respuesta utilizados

| Código | Interpretación en el proyecto |
| --- | --- |
| `200 OK` | Consulta, actualización o eliminación correcta. |
| `201 Created` | Registro creado correctamente. |
| `400 Bad Request` | JSON mal formado o estructura incorrecta. |
| `404 Not Found` | Recurso inexistente. |
| `409 Conflict` | Conflicto, por ejemplo al intentar eliminar un cliente con solicitudes. |
| `415 Unsupported Media Type` | El cuerpo de una petición que espera JSON no usa `application/json`. |
| `422 Unprocessable Content` | Datos inválidos o referencia a un cliente inexistente. |
| `500 Internal Server Error` | Error interno al completar alguna operación. |

## Pruebas manuales

Se comprobó el funcionamiento de los formularios HTML y los diez endpoints de la API usando el navegador y PowerShell, entre otros casos:

1. Crear, consultar, editar y eliminar registros de prueba.
2. Verificar que las modificaciones realizadas desde la API también se reflejen en la interfaz HTML, ya que ambas utilizan la misma base de datos.
3. Rechazar correos electrónicos duplicados y campos inválidos.
4. Responder con `404` cuando el identificador solicitado no existe.
5. Rechazar solicitudes cuyo `cliente_id` no corresponda a un cliente registrado.
6. Impedir la eliminación de clientes con solicitudes asociadas.
7. Permitir la eliminación de una solicitud sin afectar a su cliente.
8. Comprobar las rutas y la sintaxis PHP, y revisar dependencias mediante `composer audit`.

Comandos útiles para la revisión:

```powershell
php spark routes
composer audit
git status
```

## Seguridad y alcance

- Los formularios HTML de escritura utilizan protección CSRF.
- Los campos recibidos se validan del lado del servidor.
- Los detalles técnicos de errores de base de datos se registran mediante logs, evitando exponerlos como respuesta pública de la API.
- `.env` y `vendor/` no deben incorporarse al repositorio.
- **La API de esta práctica no incorpora autenticación ni autorización**. Para desplegarla públicamente habría que agregar estos controles, revisar la configuración de CORS si existe un frontend separado, configurar producción y realizar pruebas adicionales.

## Objetivo académico

Practicar la instalación de un entorno PHP nativo y el flujo **Ruta → Controller → Model / Entity → Base de datos → View o respuesta JSON**, comprendiendo tanto la interfaz web tradicional como una API REST desarrolladas sobre un mismo proyecto CodeIgniter 4.
