<?php

namespace App\Controller\Admin;


use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\CareRepository;
use App\Repository\UserRepository;
use App\Repository\ImageRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController
{
    /**
     * @Route("/patients", name="patient_index")
     */
    public function index(PatientRepository $patientRepository, PaginatorInterface $paginator, Request $request)
    {
        $patients = $patientRepository->findAll();
        $pagination = $paginator->paginate(
            $patientRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            7 /*limit per page*/
        );

        return $this->render('patient/index.html.twig', [
            'patients' => $pagination
        ]);
    }

    /**
     * @Route("/patient/{id}/edit", name="patient_edit", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function edit(Patient $patient, Request $request, EntityManagerInterface $em)
    {

        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash(
                'success',
                "Le Patient a bien été modifié dans la base de données"
            );

            return $this->redirectToRoute("patient_show", [
                "id" => $patient->getId()
            ]);
        }

        return $this->render('patient/edit.html.twig', [
            'form' => $form->createView(),
            'patient' => $patient
        ]);
    }

    /**
     * @Route("/patients/nouveau", name="patient_create")
     *
     * @return void
     */
    public function create(Request $request, EntityManagerInterface $em, UserRepository $userRepository)
    {

        $nurses = $userRepository->findBy([
            'job' => 2
        ]);

        $doctors = $userRepository->findBy([
            'job' => 1
        ]);

        $patient = new Patient;

        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($patient);
            $em->flush();

            $this->addFlash(
                'success',
                "Le nouveau patient, <strong>{$patient->getLastName()} {$patient->getFirstName()} a bien été enregistré !"
            );

            return $this->redirectToRoute("patient_show", [
                'id' => $patient->getId()
            ]);
        }

        return $this->render('patient/create.html.twig', [
            'form' => $form->createView(),
            'nurses' => $nurses,
            'doctors' => $doctors
        ]);
    }

    /**
     * @Route("/patients/{id}", name="patient_show")
     *
     * @return void
     */
    public function show(Patient $patient, CareRepository $careRepository, InterventionRepository $inters, ImageRepository $imageRepository)
    {

        $cares = $careRepository->findBy(['patient' => $patient->getId()]);

        return $this->render("patient/show.html.twig", [
            'patient' => $patient,
            'cares' => $cares,
        ]);
    }

    /**
     * @Route("/patients/{id}/delete", name="patient_delete")
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $patient = $entityManager->getRepository(Patient::class)->find($id);

        $entityManager->remove($patient);
        $entityManager->flush();

        return $this->redirectToRoute("patient_index");
    }
}
