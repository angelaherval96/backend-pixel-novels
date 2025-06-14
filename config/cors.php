<?php

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    'allowed_origins' => [
        'http://localhost:8100',//Para Ionic en desarrollo
        'http://localhost:8101',
        'http://localhost:8080', 
        'https://frontend-pixel-novels-production.up.railway.app', //Para producción
        //Para Capacitor en dispositivos nativos
        'capacitor://localhost',
        'ionic://localhost',
    ],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    'supports_credentials' => true,

];
