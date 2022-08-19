<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;
use Pecee\Http\Input\InputFile;
use Pecee\SimpleRouter\SimpleRouter;

class PetsController extends AbstractController
{
    private $petCollInst;
    private $token = 'Pancho shte se jeni';
    private $request;


    public function __construct()
    {
        $this->request = SimpleRouter::request();
        $this->petCollInst = new PetsCollection();
        parent::__construct();
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
        $id = (int)$id;

        $pet = [];
        try {
            $pet = $this->petCollInst->getPetById($id);
            if (empty($pet)) {
                $this->notFoundResponse();
            }
        } catch (\Throwable $e) {
            $this->notFoundResponse();
        }

        header("HTTP/1.1 200 OK", true, 200);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($pet);
    }

    public function create()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $this->checkUnauthorized($token);

        if (SimpleRouter::request()->getContentType() !== 'application/json') {
            SimpleRouter::response()->httpCode(400); exit(0);
        }
        $request = SimpleRouter::request();
        $requestBody = $request->getInputHandler()->all();

        $errors = [];
        if (array_key_exists('name', $requestBody) === false) {
            $errors[]['error'] = "'name' parameter is require";
        }
        elseif (strlen($requestBody['name']) < 4) {
            $errors[]['error'] = "'name' value needs to be more than 3 characters";
        }
        elseif (strlen($requestBody['name']) > 20) {
            $errors[]['error'] = "'name' value needs to be less than 20 characters";
        }
        if (array_key_exists('type', $requestBody) === false) {
            $errors[]['error'] = "'type' parameter is require";
        }
        elseif (strlen($requestBody['type']) < 3) {
            $errors[]['error'] = "'type' value needs to be more than 3 characters";
        }
        elseif (strlen($requestBody['type']) > 20) {
            $errors[]['error'] = "'type' value needs to be less than 20 characters";
        }
        if (!empty($errors)) {
            SimpleRouter::response()->httpCode(422)->json($errors);
            exit(0);
        }

        $result = $this->petCollInst->create($requestBody);
        if (empty($result)) {
            SimpleRouter::response()->httpCode(500); exit(0);
        }
        $newPet = $this->petCollInst->getPetById($result);
        header("HTTP/1.1 201 Created", true, 201);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($newPet);
    }

    public function update($id)
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $this->checkUnauthorized($token);

        $id = (int)$id;

        $pet = [];
        try {
            $pet = $this->petCollInst->getPetById($id);
            if (empty($pet)) {
                $this->notFoundResponse();
            }
        } catch (\Throwable $e) {
            $this->notFoundResponse();
        }

        $headers = getallheaders();
        if ($headers['Content-Type'] !== 'application/json') {
            $this->notFoundResponse();
        }

        $requestBody = $this->request->getInputHandler()->all();
        $errors = [];
        if (array_key_exists('name', $requestBody) === false) {
            $errors[]['error'] = "'name' parameter is require";
        }
        elseif (strlen($requestBody['name']) < 4) {
            $errors[]['error'] = "'name' value needs to be more than 3 characters";
        }
        elseif (strlen($requestBody['name']) > 20) {
            $errors[]['error'] = "'name' value needs to be less than 20 characters";
        }
        if (array_key_exists('type', $requestBody) === false) {
            $errors[]['error'] = "'type' parameter is require";
        }
        elseif (strlen($requestBody['type']) < 3) {
            $errors[]['error'] = "'type' value needs to be more than 3 characters";
        }
        elseif (strlen($requestBody['type']) > 20) {
            $errors[]['error'] = "'type' value needs to be less than 20 characters";
        }
        if (!empty($errors)) {
            SimpleRouter::response()->httpCode(422)->json($errors);
            exit(0);
        }
        $where = [
            'id' => $id
        ];
        $result = $this->petCollInst->update($where, $requestBody);
        if ($result == 0) {
            $error = [
                "error" => "Pet with id {$id} has not updated, maybe the data is the same"
            ];
            SimpleRouter::response()->httpCode(400)->json($error); exit(0);
        }
        $updatedPet = $this->petCollInst->getPetById($id);
        header("HTTP/1.1 200 OK", true, 200);
        header("Content-Type: application/json; charset=utf-8");
        return json_encode($updatedPet);
    }

    public function delete($id)
    {
        $id = (int)$id;

        $pet = [];
        try {
            $pet = $this->petCollInst->getPetById($id);
            if (empty($pet)) {
                $this->notFoundResponse();
            }
        } catch (\Throwable $e) {
            $this->notFoundResponse();
        }

        $token = $_SERVER['HTTP_AUTHORIZATION'];
        $this->checkUnauthorized($token);

        $ids = $this->petCollInst->getAllId();
        $id_array = array_column($ids, 'id');
        if (!in_array($id, $id_array)) {
            $this->notFoundResponse();
        }

        $successMessage = [
              "succsess" => "Pet with id {$id} was deleted successfully"
        ];
        $this->petCollInst->delete($id);
        header("HTTP/1.1 204", true, 204);
        header("Content-Type: application/json; charset=utf-8");

        //return json_encode($successMessage);
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