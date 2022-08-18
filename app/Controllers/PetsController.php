<?php

namespace App\Controllers;

use App\Model\Collections\PetsCollection;
use App\System\AbstractController;
use Pecee\SimpleRouter\SimpleRouter;

class PetsController extends AbstractController
{
    private $bearerToken = 'Pancho';

    public function getAllPets()
    {
        $pets        = new PetsCollection();
        $allPets     = $pets->getAllPets();
        $obj['pets'] = $allPets;

        SimpleRouter::response()->httpCode('200')->json($obj);
        exit();
    }

    public function getById($id)
    {
        $id   = (int) $id;
        $pets = new PetsCollection();
        $pet  = $pets->getPetById($id);

        if ($pet) {
            $result['pet'] = $pet;
            SimpleRouter::response()->httpCode('200')->json($result);
            exit();
        }

        SimpleRouter::response()->httpCode('404');
        exit();
    }

    public function create()
    {
        $requestContentType = $this->getContentType();

        if ($requestContentType !== 'application/json') {
            SimpleRouter::response()->httpCode('400');
            exit();
        }

        $authToken = SimpleRouter::request()->getHeader('http_authorization');

        if (!$this->verifyToken($authToken)) {
            SimpleRouter::response()->httpCode('401');
            exit();
        }

        $pets        = new PetsCollection();
        $requestBody = $this->getRequestBody();

        $errors = [];
        if (array_key_exists('name', $requestBody) && !is_string($requestBody['name'])) {
            $bodyType              = gettype($requestBody['name']);
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'name\' should be of type string, '. $bodyType . ' provided';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (is_string($requestBody['name']) && strlen($requestBody['name']) > 255) {
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'name\' should be up to 255 characters.';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (array_key_exists('type', $requestBody) && !is_string($requestBody['type'])) {
            $bodyType              = gettype($requestBody['type']);
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'type\' should be of type string, '. $bodyType . ' provided';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (is_string($requestBody['type']) && strlen($requestBody['type']) > 255) {
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'type\' should be up to 255 characters.';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        $petId = $pets->create($requestBody);

        if (is_int($petId)) {
            $result['inserted'] = $pets->getPetById($petId);
            SimpleRouter::response()->httpCode('201')->json($result);
        }

        SimpleRouter::response()->httpCode('500');
        exit();
    }

    public function update($id)
    {
        $requestContentType = $this->getContentType();

        if ($requestContentType !== 'application/json') {
            SimpleRouter::response()->httpCode('400');
            exit();
        }

        $authToken = SimpleRouter::request()->getHeader('http_authorization');

        if (!$this->verifyToken($authToken)) {
            SimpleRouter::response()->httpCode('401');
            exit();
        }

        $pets = new PetsCollection();

        if (!$pets->getPetById($id)) {
            SimpleRouter::response()->httpCode('404');
            exit();
        }

        $requestBody = $this->getRequestBody();

        $errors = [];
        if (array_key_exists('name', $requestBody) && !is_string($requestBody['name'])) {
            $bodyType              = gettype($requestBody['name']);
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'name\' should be of type string, '. $bodyType . ' provided';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (is_string($requestBody['name']) && strlen($requestBody['name']) > 255) {
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'name\' should be up to 255 characters.';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (array_key_exists('type', $requestBody) && !is_string($requestBody['type'])) {
            $bodyType              = gettype($requestBody['type']);
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'type\' should be of type string, '. $bodyType . ' provided';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        if (is_string($requestBody['type']) && strlen($requestBody['type']) > 255) {
            $errors['errors']['message']     = 'Validation Error';
            $errors['errors']['description'] = 'Field \'type\' should be up to 255 characters.';
            SimpleRouter::response()->httpCode('422')->json($errors);
            exit();
        }

        $data['name'] = $requestBody['name'];
        $data['type'] = $requestBody['type'];
        $where['id']  = $id;

        $pet = $pets->update($where, $data);

        if (is_int($pet)) {
            SimpleRouter::response()->httpCode('200')->json($pets->getPetById($id));
            exit();
        }

        SimpleRouter::response()->httpCode('500');
        exit();
    }

    /**
     * @param $id
     * @return void
     */
    public function delete($id) :void
    {
        $authToken = SimpleRouter::request()->getHeader('http_authorization');

        if (!$this->verifyToken($authToken)) {
            SimpleRouter::response()->httpCode('401');
            exit();
        }

        $pets = new PetsCollection();
        $where['id'] = $id;

        if (!$pets->getPetById($id)) {
            SimpleRouter::response()->httpCode('404');
            exit();
        }

        if (is_int($pets->delete($where))) {
            SimpleRouter::response()->httpCode('204');
            exit();
        }

        SimpleRouter::response()->httpCode('500');
        exit();
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

    public function getContentType()
    {
        return SimpleRouter::request()->getContentType();
    }

}