<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;
use Pecee\Http\Request;
use Pecee\SimpleRouter\Router;
use Pecee\SimpleRouter\SimpleRouter;

class PetsController extends AbstractController
{
    private $bearerToken = 'Pancho';

    public function getAllPets()
    {
        $pets = new PetsCollection();
        $allPets = $pets->getAllPets();

        $obj = [];
        $obj['pets'] = $allPets;

        header('HTTP/1.1 200 OK', true, 200);
        return json_encode($obj, JSON_PRETTY_PRINT);
    }

    public function getById($id)
    {
        $pets = new PetsCollection();
        $pet = $pets->getPetById($id);

        $result = [];
        $result['pet'] = $pet;

        header('HTTP/1.1 200 OK', true, 200);
        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function create()
    {
        $authToken = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if ($this->verifyToken($authToken)) {
            $pets = new PetsCollection();
            $requestBody = $this->getRequestBody();

            if (array_key_exists('name', $requestBody) && !is_string($requestBody['name'])) {
                header('HTTP/1.1 422 Unprocessable Entity ');
                exit();
            }
            $pet = $pets->create($requestBody);

            $result = [];
            $result['inserted'] = $pets->getPetById($pet);

            if (is_int($pet)) {
                header('HTTP/1.1 201 Created', true, 201);
                return json_encode($result, JSON_PRETTY_PRINT);
            }

            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }

        header('HTTP/1.1 401 Unauthorized', true, 401);
        exit();
    }

    public function update($id)
    {
        $authToken = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        if ($this->verifyToken($authToken)) {
            $pets = new PetsCollection();

            $requestBody = $this->getRequestBody();

            $result = [];

            if ($requestBody['name'] == null || $requestBody['type'] == null) {
                header("HTTP/1.1 422 Validation Error", true, 422);
                $result['result'] = "HTTP/1.1 422 Validation Error";
                return json_encode($result);
            }

            $data = $where = [];
            $data['name'] = $requestBody['name'];
            $data['type'] = $requestBody['type'];

            $where['id'] = $id;

//            if (array_key_exists('name', $requestBody) && !is_string($requestBody['name']) || array_key_exists('type', $requestBody) && !is_string($requestBody['type'])) {
//                header('HTTP/1.1 422 Unprocessable Entity ');
//                exit();
//            }

            $pet = $pets->update($where, $data);
            $updatedResult = $pets->getPetById($id);

            if (is_int($pet)) {
                header('HTTP/1.1 200 Successfully updated', true, 200);
                header("Content-Type: application/json");
                return json_encode($updatedResult, JSON_PRETTY_PRINT);
            }

            header('HTTP/1.1 500 Internal Server Error');
            exit();
        }

        header('HTTP/1.1 401 Unauthorized', true, 401);
        exit();
    }

    public function delete()
    {
        die('delete');
    }

    public function getToken()
    {
        return base64_encode($this->bearerToken);
    }

    public function verifyToken($token)
    {
        if ($token ==  'Bearer ' . $this->getToken()) {
            return true;
        }
        return false;
    }

    public function getRequestBody()
    {
        return SimpleRouter::request()->getInputHandler()->all();
    }

}