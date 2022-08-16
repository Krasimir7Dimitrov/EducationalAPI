<?php
use \Pecee\SimpleRouter\SimpleRouter;
use \App\Controllers\PetsController;

//Rest API routes Example
SimpleRouter::match(['get'],'/api/pets', [PetsController::class, 'getAllPets']);
SimpleRouter::match(['get'],'/api/pets/{id}', [PetsController::class, 'getById']);
SimpleRouter::match(['post'],'/api/pets', [PetsController::class, 'create']);
SimpleRouter::match(['put'],'/api/pets/{id}', [PetsController::class, 'update']);
SimpleRouter::match(['delete'],'/api/pets/{id}', [PetsController::class, 'delete']);

//Simple API routes exaple
//SimpleRouter::match(['get'],'/api/pets', [PetsController::class, 'getAllPets']);
//SimpleRouter::match(['get'],'/api/pet/{id}', [PetsController::class, 'getById']);
//SimpleRouter::match(['post'],'/api/pet/create', [PetsController::class, 'create']);
//SimpleRouter::match(['put'],'/api/pet/update/{id}', [PetsController::class, 'update']);
//SimpleRouter::match(['delete'],'/api/pet/delete/{id}', [PetsController::class, 'delete']);