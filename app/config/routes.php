<?php
use \Pecee\SimpleRouter\SimpleRouter;
use \App\Controllers\PetsController;

SimpleRouter::match(['get'],'/api/pets', [PetsController::class, 'getAllPets']);
SimpleRouter::match(['get'],'/api/pet/{id}', [PetsController::class, 'getById']);
SimpleRouter::match(['post'],'/api/pet', [PetsController::class, 'create']);
