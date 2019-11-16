<?php

namespace App\Controller\Admin;

use App\Repository\PatientRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
}
