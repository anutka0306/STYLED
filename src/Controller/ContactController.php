<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ContentRepository;

class ContactController extends AbstractController
{
    /**
     * @var ContentRepository
     */
    protected $page_repository;
    public function __construct(ContentRepository $repository)
    {
        $this->page_repository = $repository;
    }

    /**
     * @Route("/kontaktyi/", name="contact")
     */
    public function index(): Response
    {
        if(! $page = $this->page_repository->findOneBy(['path'=>'/kontaktyi/'])){
            throw $this->createNotFoundException('Page /kontaktyi/ not found');
        }
        return $this->render('v2/pages/contact/index.html.twig', [
            'page' => $page,
        ]);
    }
}
