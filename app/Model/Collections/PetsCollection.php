<?php

namespace App\Model\Collections;

use App\System\BaseCollection;


class PetsCollection extends BaseCollection
{
    protected $table = 'pets';


    public function getAllPets(): array
    {
        $sql = "SELECT * FROM pets";

        return $this->db->fetchAll($sql);
    }

    public function getAllId()
    {
        $sql = "SELECT id FROM pets";

        return $this->db->fetchAll($sql);
    }

    public function getPetById($id): array
    {
        $sql = "SELECT * FROM pets p WHERE p.id = :id";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function create($where)
    {
        return $this->db->insert($this->table, $where);
    }

    public function getLastInsert()
    {
        $sql = "SELECT LAST_INSERT_ID()";
        return $this->db->fetchOne($sql);
    }

    public function update($where, $data)
    {
        return $this->db->update($this->table, $where, $data);
    }
}