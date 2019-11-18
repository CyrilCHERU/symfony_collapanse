<?php

namespace App\Controller\Admin;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PatientController extends AbstractController
{
    /**
     * @Route("/admin/patients", name="admin_patient_index")
     */
    public function index(PatientRepository $patientRepository)
    {
        $patients = $patientRepository->findAll();

        // dd($patients);

        return $this->render('admin/patient/index.html.twig', [
            'patients' => $patients
        ]);
    }

    /**
     * @Route("/admin/patient/{id}/edit", name="admin_patient_edit", requirements={"id": "\d+"})
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

            return $this->redirectToRoute("admin_patient_show", [
                "id" => $patient->getId()
            ]);
        }

        return $this->render('admin/patient/edit.html.twig', [
            'form' => $form->createView(),
            'patient' => $patient
        ]);
    }

    /**
     * @Route("/admin/patient/nouveau", name="admin_patient_create")
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

            return $this->redirectToRoute("admin_patient_show", [
                'id' => $patient->getId()
            ]);
        }

        return $this->render('admin/patient/create.html.twig', [
            'form' => $form->createView(),
            'nurses' => $nurses,
            'doctors' => $doctors
        ]);
    }

    /**
     * @Route("/admin/patient/{id}", name="admin_patient_show")
     *
     * @return void
     */
    public function show(Patient $patient)
    {

        return $this->render("admin/patient/show.html.twig", [
            'patient' => $patient
        ]);
    }
}
