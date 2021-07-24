<?php

namespace App\Controller;

use App\Entity\Brand;
use App\Entity\Model;
use App\Entity\RootService;
use App\Entity\Service;
use App\Entity\Simple;
use App\Entity\Vacancy;
use App\Entity\ServiceWithout;
use App\Repository\ContentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Sitemap;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Repository\ModelRepository;
use App\Repository\PriceModelRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class PageController extends AbstractController
{
    /**
     * @var ContentRepository
     */
    protected $page_repository;
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var PaginatorInterface
     */
    protected $paginator;

   protected $price_model_repository;

    
    public function __construct(ContentRepository $repository, EntityManagerInterface $em, PaginatorInterface $paginator, PriceModelRepository $price_model_repository)
    {
        $this->page_repository = $repository;
        $this->em = $em;
        $this->paginator = $paginator;
        $this->price_model_repository = $price_model_repository;

    }

    /**
     * @Route("/vakancies/{vakancy}", name="vakancy", requirements={"token"= "\/.+\/$"})
     */
    public function vakancy($vakancy, ContentRepository $repository):Response{
        $vakancy = '/vakancies/'.$vakancy.'/';
        if ( ! $page = $this->page_repository->findOnePublishedByToken($vakancy)) {
            throw $this->createNotFoundException(sprintf('Page %s not found',$vakancy));
        }
        return $this->render('v2/pages/vacansy/item.html.twig', [
            'page' => $page,
        ]);
    }
    
    /**
     * @Route("/{token}", name="dynamic_pages",requirements={"token"= ".+\/$"})
     */
    public function index($token, EntityManagerInterface $em, PaginatorInterface $paginator, Request $request, PriceModelRepository $priceModelRepository)
    {
        if ( ! $page = $this->page_repository->findOnePublishedByToken($token)) {
            throw $this->createNotFoundException(sprintf('Page %s not found',$token));
        }

        if ($page instanceof Brand) {
            return $this->brand($page);
        }

        if ($page instanceof Model) {
            return $this->model($page, $priceModelRepository);
        }

        if ($page instanceof Service) {
            /* echo 'Page is '.$page;
            exit();*/
            return $this->service($page, $priceModelRepository);
        }

        if ($page instanceof RootService) {
           /* echo 'Page is '.$page;
            exit();*/
            return $this->rootService($page);
        }

        if ($page instanceof Simple) {
            return $this->simple($page);
        }

        if ($page instanceof Vacancy) {
            return $this->vacancy($page);
        }

        if($page instanceof ServiceWithout){
            return $this->service_without($page);
        }

        if($page instanceof Sitemap){
            $query = $em->createQuery("SELECT a FROM App\Entity\Content as a WHERE a.published = 1 ORDER BY a.id");
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                500 /*limit per page*/
            );

            // parameters to template
            return $this->render('sitemap/index.html.twig', ['pagination' => $pagination,'page'=>$page]);
        }

        throw $this->createNotFoundException('Page is instance of '.get_class($page));
    }

    /**
     * @param Sitemap $sitemap
     * @param EntityManagerInterface $em
     * @param PaginatorInterface $paginator
     * @param Request $request
     * @return Response
     */


    private function service_without(ServiceWithout $service_without)
    {
        return $this->render('v2/pages/service_without.html.twig',[
           'page' => $service_without,
        ]);
    }
    
    private function brand(Brand $brand)
    {
        $brand_name = $brand->getBrandName();
        return $this->render('v2/pages/brand.html.twig', [
            'page' => $brand,
            'brandName' => $brand_name,
        ]);
    }
    
    
    private function model(Model $model, PriceModelRepository $priceModelRepository)
    {
        $brand_name = $model->getBrandName();
        $model_id = $model->getModelId();
        if($model_id){
            $model_name = $priceModelRepository->find($model_id)->getName();
        }else{
            $model_name = null;
        }
        return $this->render('v2/pages/model.html.twig', [
            'page' => $model,
            'brandName' => $brand_name,
            'modelName' => $model_name,
        ]);
    }
    
    private function service(Service $service, PriceModelRepository $priceModelRepository)
    {
        $brand_name = $service->getBrandName();
        $model_id = $service->getModelId();
        if($model_id){
           $model_name = $priceModelRepository->find($model_id)->getName();
        }else{
            $model_name = null;
        }
        return $this->render('v2/pages/service.html.twig', [
            'page' => $service,
            'brandName' => $brand_name,
            'modelName' => $model_name,
        ]);
    }
    
    private function rootService(RootService $rootService)
    {
        if(is_null($rootService->getAdvIcon1())){
            if($rootService->getParent()->getAdvIcon1() !== null){
                $rootService->setAdvIcon1($rootService->getParent()->getAdvIcon1());
            }
            if($rootService->getParent()->getAdvIcon2() !== null){
                $rootService->setAdvIcon2($rootService->getParent()->getAdvIcon2());
            }
            if($rootService->getParent()->getAdvIcon3() !== null){
                $rootService->setAdvIcon3($rootService->getParent()->getAdvIcon3());
            }
            if($rootService->getParent()->getAdvIcon4() !== null){
                $rootService->setAdvIcon4($rootService->getParent()->getAdvIcon4());
            }
            if($rootService->getParent()->getAdvText1() !== null){
                $rootService->setAdvText1($rootService->getParent()->getAdvText1());
            }
            if($rootService->getParent()->getAdvText2() !== null){
                $rootService->setAdvText2($rootService->getParent()->getAdvText2());
            }
            if($rootService->getParent()->getAdvText3() !== null){
                $rootService->setAdvText3($rootService->getParent()->getAdvText3());
            }
            if($rootService->getParent()->getAdvText4() !== null){
                $rootService->setAdvText4($rootService->getParent()->getAdvText4());
            }
        }
        return $this->render('v2/pages/root-service.html.twig', [
            'page' => $rootService,
        ]);
    }
    
    private function simple(Simple $simple)
    {
        return $this->render('v2/pages/simple.html.twig', [
            'page' => $simple,
        ]);
    }
    
    private function vacancy(Vacancy $vacancy)
    {
        return $this->render('v2/pages/vacansy/index.html.twig', [
            'page' => $vacancy,
        ]);
    }


}
