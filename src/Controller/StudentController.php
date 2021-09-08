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
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StudentController extends AbstractController
{
   
    /**
     * Permet d'afficher la liste des students
     * 
     * @Route("/students", name="students_index")
     * @IsGranted("ROLE_USER")
     * @param StudentRepository $repo
     * @return Response
     */
    public function index(StudentRepository $repo): Response
    {
        // $repo = $this->getDoctrine()->getRepository(Student::class);
        $students = $repo->findAll();

        return $this->render('student/index.html.twig', [
            'students' => $students,
        ]);
    }
    
    /**
     * @Route("/students/new", name="students_new")
     * @IsGranted("ROLE_USER")
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
     * @Security("is_granted('ROLE_USER')")
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
     * @Route("/students/{id}/show", name="student_show", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function show(Student $student): Response
    {
        return $this->render('student/detail.html.twig', [
            'student' => $student
        ]);
    }

    /**
     * Notion de ParamConverter
     * Permet de supprimer un étudiant grâce à son id
     * @Route("/students/{id}/delete", name="student_delete", requirements={"id"="\d+"})
     * @IsGranted("ROLE_USER")
     */
    public function delete(Student $student): Response
    {
        $mat = $student->getMatricule();
        $manager = $this->getDoctrine()->getManager();
        
        $manager->remove($student);
        $manager->flush();

        $this->addFlash(
            'warning',
            "L'étudiant au matricule  <strong> {$student->getMatricule()} </strong> a bien été supprimé !"
        );

        return $this->redirectToRoute('students_index');

    }

}