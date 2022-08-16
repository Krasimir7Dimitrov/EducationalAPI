<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class PetsCollection extends BaseCollection
{
    public function getAllPets(): array
    {
        $sql = "SELECT * FROM pets";

        return $this->db->fetchAll($sql);
    }

    public function getPetById($id): array
    {
        $sql = "SELECT * FROM pets p WHERE p.id = :id";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function update($where, $data)
    {

    }
}