<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 1. Déterminer si l'application est en mode maintenance...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 2. Enregistrer le chargeur automatique d'Autoload Composer...
require __DIR__ . '/../vendor/autoload.php';

// 3. Démarrer Laravel et obtenir l'instance de l'application...
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 4. Gérer la requête entrante via le Kernel de l'application...
$response = $app->handleRequest(Request::capture());

// 5. Renvoyer la réponse au navigateur et clore la session...
$response->send();
