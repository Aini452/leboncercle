<?php
namespace App\Service;

use App\Repository\AdRepository;

class AdService{
    private $repository;

    public function __construct( AdRepository $repository ){
        $this->repository = $repository;
    }

    public function getAll(){
        return $this->repository->findAll();
    }

    public function get( $id ){
        return $this->repository->find( $id );
    }

    public function search( $term ){
        return $this->repository->search( $term );
    }
}
