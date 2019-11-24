<?php

namespace App\Controller\Admin;

use App\Entity\Intervention;
use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\CareRepository;
use App\Repository\ImageRepository;
use App\Repository\InterventionRepository;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    /**
     * @Route("/patients", name="patient_index")
     */
    public function index(PatientRepository $patientRepository)
    {
        $patients = $patientRepository->findAll();

        // dd($patients);

        return $this->render('patient/index.html.twig', [
            'patients' => $patients
        ]);
    }

    /**
     * @Route("/patient/{id}/edit", name="patient_edit", requirements={"id": "\d+"})
     *
     * @return void
     */
    public function edit(Patient $patient, Request $request, EntityManagerInterface $em)
    {

        // dd($patient);
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
     * @Route("/patient/nouveau", name="patient_create")
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
     * @Route("/patient/{id}", name="patient_show")
     *
     * @return void
     */
    public function show(Patient $patient, CareRepository $careRepository, InterventionRepository $inters, ImageRepository $imageRepository)
    {

        $cares = $careRepository->findBy(['patient' => $patient->getId()]);
        $interventions = $inters->findBy(['care' => $cares]);


        return $this->render("patient/show.html.twig", [
            'patient' => $patient,
            'cares' => $cares,
            'interventions' => $interventions,


        ]);
    }
}
