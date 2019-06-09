<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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

            $em->persist( $ad );
            $em->flush();
        
        }
        return $this->render( 'ad/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
