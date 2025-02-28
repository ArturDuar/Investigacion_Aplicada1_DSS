# investigacionAplicadaDPS1
Integrantes:
- Roberto Arturo Duarte Mejía, DM240115
- Eduardo Alfredo Ramirez Torres, RT240549
- Oscar Daniel Soto Jovel, SJ241841
- Cristian Alexander Hernández Valiente, HV240081
- Xenia Carolina Sánchez Mancia, SM232984


# Documentación de la APIrest en PHP

## Descripción General
Esta API permite gestionar usuarios y libros dentro de un sistema de librería. Incluye operaciones para crear, actualizar, eliminar y consultar tanto usuarios como libros.

---

## URL Base
```
http://localhost/index.php/
```

---

## Autenticación
Esta API utiliza **JWT** para proteger los endpoints. Se debe incluir el token en el header de cada request:
```http
Authorization: Bearer <tu_token>
```

Para obtener un token, primero debes iniciar sesión en el endpoint de login.

---

## Endpoints

### Usuarios

| Método | Endpoint | Descripción |
|--|--|--|
| `POST` | `/usuarios/login` | Iniciar sesión y obtener un JWT |
| `GET` | `/usuarios` | Listar todos los usuarios (requiere autenticación) |
| `GET` | `/usuarios/{id}` | Listar todos los usuarios (requiere autenticación) |
| `POST` | `/usuarios` | Crear un nuevo usuario (requiere autenticación)|
| `PUT` | `/usuarios/{id}` | Actualizar información de un usuario (requiere autenticación)|
| `DELETE` | `/usuarios/{id}` | Eliminar un usuario (requiere autenticación)|

### Libros

| Método | Endpoint | Descripción |
|--|--|--|
| `GET` | `/libros` | Listar todos los libros |
| `GET` | `/libros/{id}` | Listar todos los libros |
| `POST` | `/libros` | Crear un nuevo libro (requiere autenticación)|
| `PUT` | `/libros/{id}` | Actualizar información de un libro (requiere autenticación)|
| `DELETE` | `/libros/{id}` | Eliminar un libro (requiere autenticación)|

---

## Ejemplos de Request y Response

### 1. Login de Usuario
**Request:**
```http
POST /usuarios/login
Content-Type: application/json

{
    "nombreUsuario": "admin",
    "contrasennia": "123456"
}
```

**Response:**
```json
{
    "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

---

### 2. Crear un Libro
**Request:**
```http
POST /libros
Content-Type: application/json
Authorization: Bearer <tu_token>

{
    "nombreLibro": "Drácula",
    "autor": "Fidelino Santos",
    "editorial": "Santillana",
    "edicion": 1
}
```

**Response:**
```json
{
    "mensaje" : "Libro creado"
}
```

---

### 3. Listar Libros
**Request:**
```http
GET /libros
Authorization: Bearer <tu_token>
```

**Response:**
```json
[
    {
        "ID" : 1,
        "nombreLibro": "Drácula",
        "autor": "Fidelino Santos",
        "editorial": "Santillana",
        "edicion": 1
    }
]
```

---

### 4. Eliminar un Libro
**Request:**
```http
DELETE /libros/1
Authorization: Bearer <tu_token>
```

**Response:**
```json
{
    "mensaje": "Libro eliminado"
}
```

---


## Notas adicionales
- Esta API sigue un estilo **REST**.
- La autenticación es requerida para cualquier operación que no sea `login` o `listar libros`.
- El proyecto utiliza `php-jwt` para gestionar tokens.
- El archivo `libreria.sql` contiene la estructura de la base de datos requerida. Y que necesita ser ejecutada en phpMyAdmin al momento de desplegar la aplicación en Docker.

---

