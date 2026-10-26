<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

$perfil = (int) ($argv[1] ?? 1);
$perfilesPermitidos = array_map('intval', explode(',', $argv[2] ?? '1'));

$usuario = (object) ['id' => 1, 'perfil' => $perfil];

(new App\Middleware\Protection())->requerirPerfil($usuario, $perfilesPermitidos);

echo 'SIN_RESPUESTA';
