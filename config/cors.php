<?php

return [

  /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

  'paths' => ['api/*', 'sanctum/csrf-cookie'],

  'allowed_methods' => ['*'],

  'allowed_origins' => [
    'http://localhost:5173',
    'http://localhost:4173',
    'https://defensa.johnbecerra.dev'],

  'allowed_origins_patterns' => [
    '#^http://localhost:[0-9]+$#',
    '#^http://192\.168\.[0-9]+\.[0-9]+:[0-9]+$#', // Permite cualquier IP local en la red 192.168.x.x con cualquier puerto
    '#^http://10\.[0-9]+\.[0-9]+\.[0-9]+:[0-9]+$#', // Permite cualquier IP local en la red 10.x.x.x con cualquier puerto
    '#^http://172\.(1[6-9]|2[0-9]|3[0-1])\.[0-9]+\.[0-9]+:[0-9]+$#', // Permite IPs de hotspot/red privada 172.16.x.x - 172.31.x.x

  ],

  'allowed_headers' => ['*'],

  'exposed_headers' => [],

  'max_age' => 0,

  'supports_credentials' => true,

];