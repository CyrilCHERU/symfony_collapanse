<?php

namespace App\Controller\Apitest;

use App\Repository\PatientRepository;
use App\Entity\Patient;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/old")
 */
class ApiPatientController extends AbstractController
{

    /**
     * @Route("/api/patients", name="api_patient_index", methods={"GET"})
     *
     * @return void
     */
    public function index(PatientRepository $patientRepository, SerializerInterface $serializer, Request $request)
    {

        $format = $request->query->get('format', 'json');

        $patients = $patientRepository->findAll();

        $json = $serializer->serialize($patients, $format, [
            'groups' => ['patients:read', 'users:read', 'cares:read', 'interventions:read', 'images:read']
        ]);

        return new JsonResponse($json, 200, [], true);
    }

    /**
     * @Route("api/patients/{id<\d+>}", name="api_patient_show", methods={"GET"})
     *
     * @param Patient $patient
     * @param SerializerInterface $serializer
     * @return void
     */
    public function show(Patient $patient, SerializerInterface $serializer)
    {

        $json = $serializer->serialize($patient, 'json', [
            'groups' => ['patients:read', 'users:read', 'cares:read', 'interventions:read', 'images:read']
        ]);

        return new JsonResponse($json, 200, [], true);
    }
}
