<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Up2You API Documentation",
 *      description="Documentazione delle API per la gestione di eventi e iscritti"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="api_key",
 *     type="apiKey",
 *     in="header",
 *     name="X-API-KEY",
 *     description="API Key necessaria per accedere alle API"
 * )
 */
abstract class Controller
{
    //
}
