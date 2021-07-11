<?php

namespace App\Controller;

use App\Entity\SpecialOffer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SpecialOfferRepository;

class OfferController extends AbstractController
{
    /**
     * @var SpecialOfferRepository
     */
    protected $offer_repository;

    public function __construct(SpecialOfferRepository $offer_repository)
    {
        $this->offer_repository = $offer_repository;
    }

    /**
     * @Route("/offers", name="offers")
     */
    public function index(): Response
    {
        $offers = $this->offer_repository->findBy(['published' => 1]);
        return $this->render('offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    /**
     * @Route("/offer/{token}", name="dinamic_offers")
     */
    public function offer_item($token): Response{
        if ( !$offer = $this->offer_repository->findOneBy(['published'=>1, 'slug'=>$token])) {
            throw $this->createNotFoundException(sprintf('Offer %s not found',$token));
        }
        if($offer instanceof SpecialOffer){
           return $this->render('offer/offer.html.twig', ['offer'=>$offer]);
        }
    }
}
