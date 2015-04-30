<?php

namespace Restaurant\TablesBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;

class TableRepository extends DocumentRepository
{
    public function getTableById($id){
        return $this->findOneBy(array('id' => $id));
    }
    public function getAvailableTables(){
        return $this->findBy(array('available' => 1));
    }
}