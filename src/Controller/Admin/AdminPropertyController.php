<?php 

namespace App\Controller\Admin;




use App\Entity\Property;
use App\Form\PropertyType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use App\Repository\PropertyRepository;
//use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Repository\RepositoryFactory;
use Symfony\Component\HttpFoundation\Request;


class AdminPropertyController extends AbstractController
{

   /**
    * @var ObjectManager
    */
    private $em;
    



    /**
     * Undocumented variable
     *
     * @var PropertyRepository
     */
    private $repository;

    public function __construct(PropertyRepository $repository)
    {
        $this->repository = $repository;
        //$this->em = $em;
    }


    /** 
     * //Route("/admin", name="admin.property.index")///not use//////
     */
    public function index(){
        
        $properties = $this->repository->findAll();
        return $this->render('admin/property/index.html.twig', compact('properties'));
    }




    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/new.html.twig',[
       // compact('property'),
        'property'=> $property,
        'form' => $form->createView()
        ]);
    }

    

    /** 
     * //@Route("/admin/{id}", name="admin.property.edit", methods="GET|POST") ///Not use ///
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid() )
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');
        }

        return $this->render('admin/property/edit.html.twig',[
       // compact('property'),
        'property'=> $property,
        'form' => $form->createView()
        ]);
    }


    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE") // LA METHODE DELETE N'ETANT PAS DÉFINIS COMME GET ET POST, IL VA FALOIR LA DEFINIR NOUS MEME
     * @param Property $property
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property, Request $request) {
        
        $em = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $em->remove($property);
            $em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }

}
?>