<?php

require './app/Configs/Env/env.php';

if(getenv('APP_ENV') == '' || getenv('APP_ENV') == null) {
    putenv('APP_ENV=test');
}

$host = (string) $dict_ENV['HOST'];
$port = (string) $dict_ENV['PORT'];
$api_prefix = (string) $dict_ENV['PREFIX_API'];
$base_url = "http://{$host}:{$port}{$api_prefix}";

return [
    'base_url' => $base_url,
    'timeout' => 5,
    'concurrent_requests' => [1, 10, 50, 100],
    'endpoints' => [
        [
            'method' => 'GET',
            'url' => '/pessoa/items',
            'description' => 'Get the list of peoples.',
        ],
        [
            'method' => 'POST',
            'url' => '/pessoa/items',
            'description' => 'Create the new people.',
            'payload' => [
                'name' => 'Test User',
                'age' => 35,
                'email' => 'test@example.com',
                'cell' => 'Test User',
            ],
        ],
        [
            'method' => 'PUT',
            'url' => '/pessoa/items/1',
            'description' => 'Atualiza um usuário existente.',
            'payload' => [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
            ],
        ],
        [
            'method' => 'DELETE',
            'url' => '/pessoa/items/1',
            'description' => 'Exclui um usuário existente.',
        ],
    ],
];
