<?php


namespace App\ApplicationService;


use App\Domain\TagRepositoryInterface;

class TagApplicationService
{
    private $tagRepository;
    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }
    public function addTag($tag){
        $this->tagRepository->insertTag($tag);
    }
    public function findAll(){
       return $this->tagRepository->findAll();
    }


}