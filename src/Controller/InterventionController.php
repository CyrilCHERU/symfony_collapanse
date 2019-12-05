<?php

namespace App\Controller;

use DateTime;
use App\Entity\Care;
use App\Entity\Patient;
use App\Entity\Intervention;
use App\Form\InterventionType;
use App\Repository\CareRepository;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InterventionRepository;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InterventionController extends AbstractController
{
    /**
     * Présnte les interventions d'un soin d'un patient
     * 
     * @Route("/patients/{slug}/cares/{id}/interventions", name="patient_care_intervention_show")
     */
    public function show(PatientRepository $patientRepository, Care $care, InterventionRepository $inters, CareRepository $careRepository)
    {

        // $interventions = $inters->findBy(['care' => $care]);

        $interventions[] = $care->getInterventions();
        $careId = $care->getId();
        $care = $careRepository->find($careId);


        dd($interventions);



        return $this->render('intervention/show.html.twig', [

            'care' => $care,
            'interventions' => $interventions
        ]);
    }

    /**
     * Présente la liste de toutes les interventions en BDD
     * 
     * @Route("/interventions", name="intervention_index")
     *
     * @param InterventionRepository $interventions
     * @return void
     */
    public function index(InterventionRepository $interventionRepository, CareRepository $careRepository)
    {
        $interventions = $interventionRepository->findAll();

        //dd($interventions);

        return $this->render("intervention/index.html.twig", [
            'interventions' => $interventions,
            // 'cares' => $cares
        ]);
    }

    /**
     * Présente le détail d'une intervention
     * 
     * @Route("/interventions/{id<\d+>}", name="intervention_show")
     *
     * @return void
     */
    public function showInterDetails(Intervention $intervention)
    {
        return $this->render("intervention/showInterDetails.html.twig", [
            'intervention' => $intervention,
            'patient' => $intervention->getCare()->getPatient()->getFullName()
        ]);
    }


    /**
     * Formulaire d'ajout d'une intervention
     * 
     * @Route("/interventions/new", name="intervention_create")
     * @Route("/cares/{id<\d+>}/interventions/new", name="intervention_care_create")
     *
     * @return void
     */
    public function create(Care $care = null, EntityManagerInterface $em, Request $request)
    {
        $inter = new Intervention;

        if ($care) {
            $inter->setCare($care);
        }

        $inter->setDate(new DateTime());

        $form = $this->createForm(InterventionType::class, $inter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($inter);
            $em->flush();

            return $this->redirectToRoute("care_show", [
                'id' => $care->getId()
            ]);
        }

        return $this->render("intervention/new_inter.html.twig", [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/interventions/{id<\d+>}/edit", name="intervention_edit")
     *
     * @param Intervention $inter
     * @param Request $request
     * @param EntityManagerInterface $em
     * @return void
     */
    public function edit(Intervention $inter, Request $request, EntityManagerInterface $em)
    {
        $form = $this->createForm(InterventionType::class, $inter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute("care_show", [
                'id' => $inter->getCare()->getId()
            ]);
        }

        return $this->render("intervention/new_inter.html.twig", [
            'form' => $form->createView()
        ]);
    }
}
