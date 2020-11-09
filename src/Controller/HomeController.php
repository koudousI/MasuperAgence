<?php

namespace App\Controller;




use App\Entity\Property;
//use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Twig\Environment;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Repository\PropertyRepository;
use Doctrine\ORM\Repository\RepositoryFactory;

class HomeController extends AbstractController
    {


        /**
         * @var PropertyRepository
        */
        private $repository;


        /**
         * @var ObjectManager
        */
        private $em;

        /**
         * Environment variable
         *
         * @var [Environment]
         */
        private $twig;

        /**
         * le constructeur
         * 
         * constructeur pour injecter le repository
         * @var PropertyRepository
         */
        public function __construct(Environment $twig)
        {
            $this->twig = $twig;
           // $this->repository = $repository;
           // $this->em = $em;

        }

        /**
         * fonction qui affiche hello word !!!
         *
         * @return Response
         */
        public function index(PropertyRepository $repository): Response
        {
            $properties = $repository->findLatest();
            // onrecupère la liste des 4 derniers (voir findLatest)
            //$properties = $this->getDoctrine()->getRepository(Property::class)->findLatest();
           
            // $properties =$this->repository->findLatest();

            //$em = $em = $this->getEntityManager();

            //$properties =$this->repository->findAllVisible();
            dump($properties);
            
            /* 
            $properties = $em->getRepository(Property::class)->findLatest();  
            dump($properties);
            exit('arrèt'); */

            //return new Response($content= 'Salut les gens');
              /*return $this->render('pages/index.html.twig', [
                'properties' => $properties,
            ]);*/

           return new Response($this->twig->render($content = 'pages/home.html.twig',
            ['properties'=>$properties,]
            )); 
        }
    }
    

?>