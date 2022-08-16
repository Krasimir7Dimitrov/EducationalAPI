<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;

class PetsController extends AbstractController
{
    private $petCollInst;

    public function __construct()
    {
        $this->petCollInst = new PetsCollection();
        parent::__construct();
    }

    public function getAllPets()
    {
        $allPets = $this->petCollInst->getAllPets();

        return json_encode($allPets);
    }

    public function getById($id)
    {
        $pet = $this->petCollInst->getPetById($id);

        return json_encode($pet);
    }

    public function create()
    {
        $request = $_REQUEST;
        $this->petCollInst->create($request);
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