<?php
use \Pecee\SimpleRouter\SimpleRouter;
use \App\Controllers\PetsController;

//Rest API routes Example
//SimpleRouter::match(['get'],'/api/pets', [PetsController::class, 'getAllPets']);
//SimpleRouter::match(['get'],'/api/pets/{id}', [PetsController::class, 'getById']);
//SimpleRouter::match(['post'],'/api/pets', [PetsController::class, 'create']);
//SimpleRouter::match(['put'],'/api/pets/{id}', [PetsController::class, 'update']);
//SimpleRouter::match(['delete'],'/api/pets/{id}', [PetsController::class, 'delete']);

//Simple API routes Example
SimpleRouter::get('/api/pets', [PetsController::class, 'getAllPets']);
SimpleRouter::get('/api/pet/{id}', [PetsController::class, 'getById']);
SimpleRouter::post('/api/pet/create', [PetsController::class, 'create']);
SimpleRouter::post('/api/pet/update/{id}', [PetsController::class, 'update']);
SimpleRouter::post('/api/pet/delete/{id}', [PetsController::class, 'delete']);