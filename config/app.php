<?php
// config/app.php - 应用配置
return [
    'name' => '森码云实人认证系统',
    'version' => '1.0.0',
    'domain' => 'face.builds.codes',
    'timezone' => 'Asia/Shanghai',
    'charset' => 'UTF-8',
    'debug' => getenv('APP_DEBUG') === 'true',
    'auth' => [
        'token_expire' => 15,
        'max_attempts' => 5,
        'lockout_minutes' => 30,
        'liveness_threshold' => 0.75,
        'face_match_threshold' => 80.0,
    ],
    'upload' => [
        'max_size' => 10485760,
        'allowed_types' => ['image/jpeg','image/png','image/webp'],
        'face_dir' => __DIR__.'/../public/uploads/faces',
    ],
];