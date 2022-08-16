<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;

class PetsController extends AbstractController
{
    public function getAllPets()
    {
        $pets = new PetsCollection();
        $allPets = $pets->getAllPets();

        return json_encode($allPets);
    }

    public function getById($id)
    {
        $pets = new PetsCollection();
        $pet = $pets->getPetById($id);

        return json_encode($pet);
    }

    public function create()
    {
        die('create');
    }

    public function update()
    {
        die('update');
    }

    public function delete()
    {
        die('delete');
    }

}