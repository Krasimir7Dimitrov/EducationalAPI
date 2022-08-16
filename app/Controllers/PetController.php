<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;

class PetController extends AbstractController
{

    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (strtoupper($method) == 'GET') {
            $queryString = $_SERVER['QUERY_STRING'];
            var_dump($queryString); die();
            $pet->getPetById($id);
        }
    }
}