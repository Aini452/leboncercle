<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\DateInterval;

use App\Service\AdService;
use App\Entity\Ad;
use App\Form\AdType;

class AdController extends AbstractController
{

    private $adService;

    public function __construct( AdService $adService){
        $this->adService = $adService;
    }
    /**
     * @Route("/ad-list", name="adList")
     */
    public function list(Request $request)
    {
        $query = $request->query->get('query');

        if(!empty($query)){
            $ads = $this->adService->search($query);
        } else {
            $ads = $this->adService->getAll();
        }
        return $this->render('ad/index.html.twig', 
            array('ads' => $ads)
        );
    }

    /**
     * @Route("/add-ad", name="addAd")
     */
    public function add(Request $request){

        $ad = new Ad();
        $form = $this->createForm( AdType::class, $ad);

        $form->handleRequest( $request );
        if( $form->isSubmitted() && $form->isValid() ){
            $em = $this->getDoctrine()->getManager();

            $ruser = $em->getRepository('App:User')->findOneBy( array() );
            $ad->setOwner( $ruser);
            $ad->setPrice(1245);
            $now = new \DateTime();
            $ad->setCreationDate( $now );
            
            $ad->setExpirationDate( $now );

            $em->persist( $ad );
            $em->flush();
        
        }
        return $this->render( 'ad/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * @Route("/show-ad/{id}", name="show-ad",
     *  requirements = { "id" = "\d+" })
     */
    public function show(int $id){
        return $this->render('ad/show.html.twig', 
        array('ad' => $this->adService->get($id)));
    }
}
