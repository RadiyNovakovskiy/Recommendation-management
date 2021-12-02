<?php

namespace App\Repository;

interface SearchRepositoryInterface
{
    /**
     * @return Post[]
     */
    public function getAll(): array;

}