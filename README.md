
# Guía de Instalación del Proyecto

## Requisitos Previos

Antes de comenzar, asegúrate de tener instalados los siguientes componentes:

- PHP 7.4 o superior
- Composer
- Laravel 8 o superior
- Servidor web (Apache, Nginx, etc.)
- OpenWeatherMap API Key

## Pasos de Instalación

### 1. Clonar el Repositorio

Clona el repositorio del proyecto desde GitHub o descarga el código fuente.

```bash
git clone https://github.com/tu_usuario/tu_proyecto.git
cd tu_proyecto
```

### 2. Instalar Dependencias

Instala las dependencias de Composer necesarias para el proyecto.

```bash
composer install
```

### 3. Configurar Variables de Entorno

Copia el archivo de ejemplo `.env` a un nuevo archivo `.env`.

```bash
cp .env.example .env
```

Abre el archivo `.env` en tu editor de texto favorito y agrega tu clave de API de OpenWeatherMap. Busca la línea que dice `API_KEY_WEATHER=` y asigna tu clave.

```env
API_KEY_WEATHER=clase de OpenWeatherMap
```

### 4. Generar la Clave de la Aplicación

Genera la clave de la aplicación de Laravel.

```bash
php artisan key:generate
```

### 5. Configurar el Servidor Web

Configura tu servidor web (Apache, Nginx, etc.) para apuntar al directorio `public` de tu proyecto Laravel. Asegúrate de que el servidor esté configurado correctamente para manejar las solicitudes.

### 6. Probar Comandos Artisan

Prueba los comandos Artisan personalizados para verificar que están funcionando correctamente.

```bash
# Obtener el clima actual para una ubicación específica
php artisan current Havana,CU --u=imperial   

# Obtener el pronóstico del tiempo para una ubicación específica por dias.
php artisan forecast Madrid,ES --d=4 --u=imperial

#Obtener el pronostico con preguntas 
php artisan forecast:ask 
```

### 8. Probar Endpoints API

Puedes probar los endpoints de la API utilizando herramientas como Postman o cURL.

```bash
# Obtener el clima actual para una ubicación específica
curl -X GET "{{ruta}}/weather?location=Havana,CU&units=imperial"

# Obtener el pronóstico del tiempo para una ubicación específica por dias.
curl -X GET "{{ruta}}/forecast?location=Havana,CU&units=imperial&days=5"

#Obtener el pronostico con preguntas
curl -X GET "{{ruta}}/forecast/ask?units=imperial&days=5"
```
