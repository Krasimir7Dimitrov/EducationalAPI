<?php

namespace App\Model\Collections;

use App\System\BaseCollection;

class PetsCollection extends BaseCollection
{
    protected $table = 'pets';

    public function getAllPets()
    {
        $sql = "SELECT * FROM $this->table";

        return $this->db->fetchAll($sql);
    }

    public function getPetById($id)
    {
        $sql = "SELECT * FROM $this->table p WHERE p.id = :id";

        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function create($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($where, $data)
    {
        return $this->db->update($this->table, $where, $data);
    }

    public function delete($where)
    {
        return $this->db->delete($this->table, $where);
    }
}