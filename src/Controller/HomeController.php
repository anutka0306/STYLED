<?php

namespace App\Controller;

use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

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
