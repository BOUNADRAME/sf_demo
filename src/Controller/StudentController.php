<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Student;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
    #[Route('/students', name: 'students_index')]
    public function index(StudentRepository $repo): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Student::class);
        $students = $repo->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }

    // /**
    //  * Permet d'afficher un étudiant grâce à son id
    //  * @Route("students/{id}", name="student_show", requirements={"id"="\d+"})
    //  */
    // public function show($id, StudentRepository $repo): Response
    // {
    //     $student = $repo->findOneById($id);

    //     return $this->render('student/detail.html.twig', [
    //         'student' => $student
    //     ]);
    // }
    
    /**
     * @Route("/students/new", name="students_new")
     */
    public function create(Request $request): Response
    {   
        $student = new Student();        
        
        $form = $this->createForm(StudentType::class, $student);
        // recuperation et persitence des données en base
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            // save images
            foreach($student->getImages() as $image){
                $image->setStudent($student);
                $manager->persist($image);
            }

            $student->setAuthor($this->getUser());

            $manager->persist($student);
            $manager->flush();
            
            // notify 
            $this->addFlash(
                'success',
                "L'étudiant <strong> {$student->getPrenom()} </strong> a bien été inscrit !"
            );
            // redirection vers la liste des students
            return $this->redirectToRoute('student_show', [
                'id' => $student->getId()
            ]);
        }

        return $this->render('student/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de modifier un student 
     * @Route("/students/{id}/edit", name="student_edit", requirements={"id"="\d+"})
     * @return Response
     */
    public function edit(Student $student, Request $request): Response
    {
        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager = $this->getDoctrine()->getManager();
            // save images
            foreach($student->getImages() as $image){
                $image->setStudent($student);
                $manager->persist($image);
            }

            $manager->persist($student);
            $manager->flush();
            
            // notify 
            $this->addFlash(
                'success',
                "Les modifications sur l'étudiant <strong> {$student->getPrenom()} </strong> ont bien été enregistrées !"
            );
            // redirection vers la liste des students
            return $this->redirectToRoute('student_show', [
                'id' => $student->getId()
            ]);
        }
        
        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
            'student' => $student
        ]);
    }

    /**
     * Notion de ParamConverter
     * Permet d'afficher un étudiant grâce à son id
     * @Route("/students/{id}", name="student_show", requirements={"id"="\d+"})
     */
    public function show(Student $student): Response
    {
        return $this->render('student/detail.html.twig', [
            'student' => $student
        ]);
    }

}