<?php 

namespace App\Service;

use Twig\Environment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class Pagination {
    private $entityClass;
    private $limit = 10;
    private $currentPage = 1;
    private $manager;
    private $twig;
    private $route;
    private $templatePath;

    public function __construct(EntityManagerInterface $manager, Environment $twig, RequestStack $request, $templatePath){
        $this->route           = $request->getCurrentRequest()->attributes->get('_route');
        $this->manager         = $manager;
        $this->twig            = $twig;
        $this->twig            = $twig;
        $this->templatePath    = $templatePath;
    }

    public function setTemplatePath($templatePath){
        $this->templatePath = $templatePath;

        return $this;
    }

    public function getTemplatePath(){
        return $this->templatePath;
    }

    public function setRoute($route){
        $this->route = $route;

        return $this;
    }

    public function getRoute(){
        return $this->route;
    }

    public function display(){
        $this->twig->display($this->templatePath, [
            'page' => $this->currentPage,
            'pages' => $this->getPages(),
            'route' => $this->route
        ]);
    }

    public function getPages(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifier l'entité sur laquelle 
            on dois paginer, utilisez la méthode setEntityClass() de votre objet de Pagination !");
        }
        // connaitre le total des enregistrement de la page
        $repo = $this->manager->getRepository($this->entityClass);
        $total = count($repo->findAll());

        // faire la division, l'arrondi et la renvoyé
        $pages = ceil($total / $this->limit);

        return $pages;
    }

    public function getData(){
        if(empty($this->entityClass)){
            throw new \Exception("Vous n'avez pas spécifier l'entité sur laquelle 
            on dois paginer, utilisez la méthode setEntityClass() de votre objet de Pagination !");
        }
        // calculer l'offset
        $offset = $this->currentPage * $this->limit - $this->limit;

        // demander au repository de retrouver les elements
        $repo = $this->manager->getRepository($this->entityClass);
        $data = $repo->findBy([], [], $this->limit, $offset);

        // renvoyer les elements en question
        return $data;
    }

    public function setPage($page){
        $this->currentPage = $page;

        return $this;
    }

    public function getPage(){
        return $this->currentPage;
    }

    public function setLimit($limit){
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(){
        return $this->limit;
    }

    public function setEntityClass($entityClass){
        $this->entityClass = $entityClass;

        return $this;
    }

    public function getEntityClass(){
        return $this->entityClass;
    }
}