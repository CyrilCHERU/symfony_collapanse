<?php

namespace App\Controller;

use App\Entity\Care;
use App\Form\CareType;
use App\Entity\Patient;
use App\Repository\CareRepository;
use Doctrine\ORM\EntityManagerInterface;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CareController extends AbstractController
{
    /**
     * @Route("/cares", name="care_index")
     */
    public function index(CareRepository $careRepository, Request $request, PaginatorInterface $paginator)
    {
        $cares = $careRepository->findAll();

        $pagination = $paginator->paginate(
            $careRepository->findAll(),
            $request->query->getInt('page', 1), /*page number*/
            9 /*limit per page*/
        );

        return $this->render('care/index.html.twig', [
            'cares' => $pagination,
        ]);
    }


    /**
     * @Route("/cares/new", name="care_create")
     * @Route("/patients/{id}/cares/new", name="care_patient_create")
     *
     * @return void
     */
    public function create(Patient $patient = null, Request $request, EntityManagerInterface $em)
    {
        $care = new Care;

        if ($patient) {
            $care->setPatient($patient);
        }

        $care->setCreatedAt(new DateTime());

        $form = $this->createForm(CareType::class, $care);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($care);
            $em->flush();

            return $this->redirectToRoute("care_show", [
                'id' => $care->getId()
            ]);
        }

        return $this->render("care/create.html.twig", [
            'form' => $form->createView(),
            'patient' => $patient
        ]);
    }
    /**
     * @Route("/cares/{id}", name="care_show")
     *
     * @param Care $care
     * @return void
     */
    public function show(Care $care)
    {

        return $this->render("care/showcare.html.twig", [
            'care' => $care,
            'patient' => $care->getPatient()->getFullName(),
            'patientId' => $care->getPatient()->getId()
        ]);
    }

    /**
     * @route("/patients/{id}/cares", name="care_followedcares")
     *
     * @return void
     */
    public function showFollowedCares(Patient $patient)
    {
        return $this->render("care/showFollowed.html.twig", [
            'patient' => $patient,
            'cares' => $patient->getCares(),
        ]);
    }

    /**
     * @Route("/cares/{id}/edit", name="care_edit")
     *
     * @return void
     */
    public function edit(Request $request, Care $care, EntityManagerInterface $em)
    {


        // dd($patient);
        //dd($care);

        $form = $this->createForm(CareType::class, $care);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute("care_followedcares", [
                'id' => $care->getPatient()->getId()
            ]);
        }

        return $this->render("care/edit.html.twig", [
            'care' => $care,
            'patient' => $care->getPatient()->getFullName(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/cares/{id}/delete", name="care_delete")
     *
     * @param integer $id
     * @return void
     */
    public function delete(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $care = $entityManager->getRepository(Care::class)->find($id);

        $entityManager->remove($care);
        $entityManager->flush();

        return $this->redirectToRoute("care_followedcares", [
            'id' => $care->getPatient()->getId()
        ]);
    }
}
