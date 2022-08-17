<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;
use Pecee\SimpleRouter\SimpleRouter;

class PetsController extends AbstractController
{
    private $petCollInst;
    private $token = 'Pancho shte se jeni';


    public function __construct()
    {
        $this->petCollInst = new PetsCollection();
        parent::__construct();
        $this->getToken();
    }

    public function getAllPets()
    {
        $allPets = $this->petCollInst->getAllPets();
        header("HTTP/1.1 200 OK", true, 200);
        header("Content-Type: application/json; charset=utf-8");
        //header("Authorization: Bearer {$this->getToken()}");

        $pets['pets'] = $allPets;
        return json_encode($pets);
    }

    public function getById($id)
    {
        $ids = $this->petCollInst->getAllId();
        $id_array = array_column($ids, 'id');
        if (!in_array($id, $id_array)) {
            $this->notFoundResponse();
        }

        $pet = $this->petCollInst->getPetById($id);
        header("HTTP/1.1 200 OK", true, 200);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($pet);
    }

    public function create()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $this->checkUnauthorized($token);

        $headers = getallheaders();
        if ($headers['Content-Type'] !== 'application/json') {
            $this->notFoundResponse();
        }
        $request = SimpleRouter::request();
        $requestBody = $request->getInputHandler()->all();

        if (array_key_exists('name', $requestBody) === false || array_key_exists('type', $requestBody) === false) {
            header("HTTP/1.1 422 Validation error", true, 422);
        }

        $result = $this->petCollInst->create($requestBody);
        if (!is_int($result)) {
            $this->notFoundResponse();
        }
        $newPet = $this->petCollInst->getPetById($result);
        header("HTTP/1.1 201 Created", true, 201);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($newPet, JSON_PRETTY_PRINT);
    }

    public function update($id)
    {
        $ids = $this->petCollInst->getAllId();
        $id_array = array_column($ids, 'id');
        if (!in_array($id, $id_array)) {
            $this->notFoundResponse();
        }
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $this->checkUnauthorized($token);

        $headers = getallheaders();
        if ($headers['Content-Type'] !== 'application/json') {
            $this->notFoundResponse();
        }
        $request = SimpleRouter::request();
        $requestBody = $request->getInputHandler()->all();
        if (array_key_exists('name', $requestBody) === false || array_key_exists('type', $requestBody) === false) {
            header("HTTP/1.1 422 Validation error", true, 422);
            die();
        }
        $where = [
            'id' => $id
        ];
        $this->petCollInst->update($where, $requestBody);
        $updatedPet = $this->petCollInst->getPetById($id);
        header("HTTP/1.1 200 OK", true, 200);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($updatedPet, JSON_PRETTY_PRINT);
    }

    public function delete()
    {
        die('delete');
    }

    private function notFoundResponse()
    {
        header("HTTP/1.1 404 Not Found");
        die();
    }

    private function getToken()
    {
        return base64_encode($this->token);
    }

    private function checkUnauthorized($token)
    {
        $realToken = str_replace("Bearer ", "",$token);
        if ($this->getToken() === $realToken) {
            return true;
        }

        header("HTTP/1.1 401 Unauthorized");
        die();

    }

}