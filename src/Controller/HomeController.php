<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use App\Service\Mobile_Detect;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(ContentRepository $repository)
    {
        $page = $repository->findOneBy(['path'=>'/']);
        $gallery = $this->getGalleryImages();

        
        return $this->render('v2/pages/home/index.html.twig', [
            'page' => $page,
            'gallery'=> $gallery,
        ]);
    }

    /**
     * @Route ("/promo", name="promo")
     */
    public function promo(ContentRepository $repository){
        $page = $repository->findOneBy(['path'=>'/']);
        $gallery = $this->getGalleryImages();
        $detect = new Mobile_Detect();
        if($detect->isMobile()){
           $is_mobile = 1;
        }else{
            $is_mobile = 0;
        }
        return $this->render('v2/pages/home/mobile_index.html.twig', [
            'page' => $page,
            'gallery'=> $gallery,
            'isMobile' => $is_mobile,
        ]);
    }

    private function getGalleryImages(){
        $finder = new Finder();
        $filesystem = new Filesystem();
        if($filesystem->exists($_SERVER['DOCUMENT_ROOT'].'/images/gallery')){
            $finder->files()->name(['*.jpeg','*.jpg','*.png'])->in($_SERVER['DOCUMENT_ROOT'].'/images/gallery');
            $files = array();
            foreach ($finder as $file){
                $files[] = '/images/gallery/'.$file->getFilename();
            }
        }else{
            $files = null;
        }


        return $files;
    }
}
