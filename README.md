# investigacionAplicadaDPS1
Integrantes:
- Roberto Arturo Duarte Mejía, DM240115
- Eduardo Alfredo Ramirez Torres, RT240549
- Oscar Daniel Soto Jovel, SJ241841
- Cristian Alexander Hernández Valiente, HV240081
- Xenia Carolina Sánchez Mancia, SM232984
- Salvador Enrique Delgado Peñate, DP240093

# Documentación de Docker

## Dockerfile
- 1.Dockerfile
- Descripción:
Este archivo lo que hace es definir las imágenes que se utilizaran con Docker, en este caso una API REST implementada con lenguaje de PHP.

Contenido de Dockerfile:

    •FROM php:8.2-apache:
        Usa una imagen de PHP 8.2 con apache para levantar el servidor web del API.

    •RUN docker-php-ext-install mysqli pdo pdo_mysql:
        Se instala la extensión de PHP para que la API pueda conectarse a una base de datos MySQL.

    •RUN a2enmod rewrite:
        Se esta habilitando el módulo de rewrite de apache, se necesita para el manejo de URLs amigables y redirecciones.

    •COPY src/ /var/www/html/:
        Aquí se esta configurando de que los archivos de la aplicación que estén el archivo src/ se copiaran al directorio virtual de /var/www/html/  que se encuentra dentro del contenedor creado.

    •RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html:
        Se esta cambiado los permisos y la propiedad de los archivos del contendor dentro de  /var/www/html para que apache pueda acceder a los archivos sin tener ningún conflicto con los permisos.

    •EXPOSE 80:
        Hace que se exponga el puerto 80 del contenedor creado.
    
    •CMD ["apache2-foreground"]:
        Se inicia apache cuando el contenedor se este ejecutando y hace que apache se ejecute en primer plano.

## Docker Compose 
- 2.docker-compose.yml
- Descripción:
Este archivo esta definiendo el entorno de docker el cual será un multi-contenedor utilizando docker-compose eh incluye 3 servicios dentro que serían PHP, MySQL y phpmyadmin.

Contenido docker-composel.yml:

    •version: '3.8'
        Especificamos la version del archivo de Docker Compose.

    •Servicio: 
        dentro de servicio justamente se hara la configuración de los servicios necesario para la API.

    •Servicio: app:
    o	Build: . : Construye la imagen de Docker(Dockerfile) en el directorio actual.
    o	ports: "8080:80":  Se mapea el puerto 80 del contenedor en el puerto 8080.
    o	depends_on: db: Se inicia el servicio de db(MySQL) antes que el servicio de app.
    o	networks: appnet: conecta el servidor de red appnet.

    •Servicio: db:
    o	image: mysql:5.7: Usa la imagen de MySQL 5.7.
    o	environment:
        	MYSQL_ROOT_PASSWORD: 123456: define la contraseña de root de MySQL.
        	MYSQL_DATABASE: librería: Crea una base de datos llamada librería.
    o	volumes: db_data:/var/lib/mysql: Crea un volumen para persistir los datos de la DB.
    o	 networks: appnet: conecta el servidor de red appnet.

    •Servicio: phpmyadmin:
    o	image: phpmyadmin/phpmyadmin: Usa la imagen de phpmyadmin.
    o	ports: "8081:80": mapea el puerto 80 del contenedor en el puerto 8081.
    o	environment: PMA_HOST: db: Hace la configuración de phpmyadmin para conectarse.
    o	depends_on: db: Se asegura de q el servicio db se inicie antes de phpmyadmin.
    o	networks: appnet: conecta el servidor de red appnet.

    •Network:
    o	appnet: Define una red de tipo bridge para que los servicios puedan comunicarse entre sí.

    •Volumes:
    o	db_data: Define un volumen para que los datos que vayan a la base de datos persistan.

## Ejecución de los contenedores
- 3.deployment.yml
- Descripción:
En este archivo se define el despliegue en kubernetes para la aplicación PHP en los que se incluyen un Deployment, un Service y un HorizontalPodAutoscaler para gestionar la escalabilidad de la aplicación.

Contenido deployment.yml:

    •Deployment:
    o	apiVersion: apps/v1: Esta especificando la version API de Kubernetes.
    o	kind: Deployment: Esta definiendo que es un recurso de tipo Deployment.
    o	metadata: name: php-login-app:  Es el nombre del despliegue.
    o	spec: replicas: 3:  aquí estamos creando específicamente 3 réplicas del pod.  
    o	selector: matchLabels: app: php-login-app: Se esta seleccionando el pod con la etiqueta de app:php-login-app.
    o	template:
        	containers:
            	name: php-login-app:  Este es el nombre del contenedor.
            	image: my-docker-image:latest:  Se utiliza la imagen de Docker de my-docker-image:latest.
            	ports: containerPort: 80:  exponiendo el puerto 80 del contenedor de Docker.

    •Service:
    o	apiVersion: v1: Esta especificando la version de API de Kubernetes.
    o	kind: Service: Está definiendo que es un recurso de tipo Service.
    o	metadata: name: php-login-service:  Este es el nombre del servicio.
    o	spec: type: LoadBalancer: Utilizamos un balanceador de carga el cual expone el servicio externamente.
    o	ports: protocol: TCP, port: 80, targetPort: 80: Mapea el puerto 80 del servicio al puerto 80 del contenedor.

    •HorizontalPodAutoscaler:
    o	apiVersion: autoscaling/v2: Se esta especificando la version de la API de autoscaling.
    o	kind: HorizontalPodAutoscaler: Se define que es un recurso HorizontalPodAutoscaler.
    o	metadata: name: php-login-hpa: Es el nombre del autoscaler.
    o	spec: scaleTargetRef: name: php-login-app: Se esta definiendo el despliegue en el que se escalara de manera automática.
    o	minReplicas: 1:  Es el número mínimo de replicas que se generaran.
    o	maxReplicas: 5: Es el número máximo de replicas que se generaran.
    o	metrics(type: Resource, resource: name: cpu, target: averageUtilization: 80 ):Escala de manera automática pero basándose en el uso del CPU con un 80%.

## Notas adicionales
- La aplicación PHP se ejecuta en un contenedor Docker con la imagen de Docker 
- El servicio de base de datos se ejecuta en un contenedor Docker con la imagen de Docker
- El servicio de phpmyadmin se ejecuta en un contenedor Docker con la imagen de Docker
## comando para ejecutar

- Para la creacion y el Levantamiento del entorno de nuestro proyecto en docker, se ejecuta este comando:
- docker-compose up -d##

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

