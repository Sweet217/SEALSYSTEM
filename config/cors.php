<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Laravel CORS Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Cross-Origin Resource Sharing (CORS) settings here to allow
    | cross-origin requests in your application.
    |
    */

    'paths' => ['api/*', '*'], // Habilita CORS en rutas API y todas las rutas

    'allowed_methods' => ['*'], // Permitir todos los métodos HTTP (GET, POST, PUT, DELETE, etc.)

    'allowed_origins' => ['*'], // Permitir solicitudes desde todos los orígenes. Cambia '*' a tus dominios si es necesario.

    'allowed_origins_patterns' => [], // Patrones de origen permitidos

    'allowed_headers' => ['*'], // Permitir todos los encabezados

    'exposed_headers' => [], // Headers que se expondrán al navegador

    'max_age' => 0, // Tiempo máximo en segundos que se permiten las solicitudes preflight en caché

    'supports_credentials' => false, // Cambia a `true` si necesitas soporte para credenciales (cookies)
];
