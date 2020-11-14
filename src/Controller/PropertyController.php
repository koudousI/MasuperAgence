<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Form\ContactType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;




    Class PropertyController extends AbstractController{


        /**
         * Undocumented variable
         *
         * @var [tyPropertyRepositorype]
         */
        private $repository;


        /**
         * constructeur pour injecter le repository
         * @var PropertyRepository
         */
        public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
        {
            $this->repository = $repository;
            $this->em = $em;
        }


        /**
         * @Route("/biens", name="property.index")
         */
      /*   public function index(): Response
        {

            /* $property = new Property();
            $property->setTitle($title ='Mon premier bien')
                    ->setPrice($price = 200000)
                    ->setRoom($room = 4)
                    ->setBedrooms($bedrooms = 3)
                    ->setDescription($description = 'une petite description')
                    ->setSurface($surface = 60)
                    ->setFloor($floor = 4)
                    ->setHeat($heat = 1)
                    ->setCity($city = 'Rennes')
                    ->setAddress($address = '14 Villa de Moravie')
                    ->setPostalCode($postalcode = '35000');
 
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush(); */

           /*  $repository = $this->getDoctrine()->getRepository(Property::class);
            dump($repository); */

/*
            $property =$this->repository->findAllVisible();
            dump($property);

            return $this->render($content = 'properties/index.html.twig',
            ['current_menu' => 'properties']
            );

        } */

        /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );
        return $this->render('properties/index.html.twig', [
            'current_menu' => 'properties',
            'properties'   => $properties,
            'form'         => $form->createView()
        ]);
    }


    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     * @param Property $property
     * @return Response
     */
    public function show(Property $property, string $slug, Request $request, ContactNotification $notification): Response
    {
        if ($property->getSlug() !== $slug) {
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }


        /*
        * Envoie du form contact à la vue
        */
        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify($contact);
            $this->addFlash('success', 'Votre email a bien été envoyé');
            return $this->redirectToRoute('property.show', [
                'id'   => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }


        return $this->render('properties/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            'form'         => $form->createView()
        ]);
    }
        
    }
?>