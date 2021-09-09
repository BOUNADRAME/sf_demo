<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;

class Stats {
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    public function getStats(){
        $users    = $this->getUsersCount();
        $students = $this->getStudentsCount();

        return compact('users', 'students');
    }

    public function getUsersCount(){
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getStudentsCount(){
        return $this->manager->createQuery('SELECT COUNT(s) FROM App\Entity\Student s')->getSingleScalarResult();
    }

    public function requestWithJoin(){
        // $best = $this->manager->createQuery(
        //     'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.picture
        //      FROM App\Entity\Comment c
        //      JOIN c.ad a
        //      JOIN a.author u
        //      GROUP BY a
        //      ORDER BY note DESC'
        //  )
        //  ->setMaxResults(5)
        //  ->getResult();
    }
}