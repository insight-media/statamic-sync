<?php
// config for InsightMedia/StatamicSync
return [
    "ssh" => [
        "user" => env('SSH_USER', null),
        "host" => env('SSH_HOST', null),
        "port" => env('SSH_PORT', 22),
        "path" => env('SSH_PATH', null)
    ],
    "paths" => [
        'content',
        'users',
        'resources/blueprints',
        'resources/fieldsets',
        'resources/forms',
        'resources/users',
        'storage/forms'
    ]
];
