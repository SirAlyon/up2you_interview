# Up2You API

**Progetto back-end per la gestione di eventi e iscritti realizzato con Laravel 12.**

## Installazione

```
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Generazione della Documentazione Swagger
    
Pubblica i file di configurazione di L5-Swagger:

```
php artisan vendor:publish --provider "L5Swagger\L5SwaggerServiceProvider"
php artisan l5-swagger:generate
```

La documentazione aggiornata sarà disponibile all'indirizzo:
http://localhost:8000/api/documentation

Utilizza il pulsante Authorize per inserire l'API Key (tramite l'header X-API-KEY) e testare gli endpoint protetti.

## Avvio del Progetto

```
php artisan serve
```
