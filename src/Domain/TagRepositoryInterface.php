<?php


namespace App\Domain;


use App\Entity\Tag;

interface TagRepositoryInterface
{
    public function insertTag(Tag $tag):void;
    public function findAll();

}