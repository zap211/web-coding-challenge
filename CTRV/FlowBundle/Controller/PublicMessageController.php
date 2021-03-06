<?php

namespace CTRV\FlowBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use CTRV\CommonBundle\DependencyInjection\Constants;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\FlowBundle\Entity\PublicMessage;

/**
 * PublicMessage controller.
 *
 * @Route("/publicMessage")
 */
class PublicMessageController extends Controller
{
     /**
     * Charge les donnees des messages spécifié en AJAX (sous forme de tableau paginé)
     * @Route("/list", name="publicMessage")
     * @Template()
     */
    public function loadPublicMessageAction () {
    	
    	$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//paginations
        $nb_entities = $publicMessages = $this->getDoctrine()->getRepository('CTRVFlowBundle:PublicMessage')->getPublicMessageNumber($city);
        $nb_entities_page = Constants::publicmessage_number_per_page;
        $nb_pages = ceil($nb_entities/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $publicMessages = $this->getDoctrine()->getRepository('CTRVFlowBundle:PublicMessage')->getPublicMessage($city, $offset, $nb_entities_page);
       	
       	return  array (
            'entities' => $publicMessages,
        	'nb_pages' => $nb_pages,
        	'page' => $page,
        	'nb_entities' => $nb_entities,
       			'city'=>$currentCity
        );
    }  

    /**
     * @Route("/publicMessageAbuse",name="publicMessageAbuse")
     * @Template()
     * Retourne la liste de tous les messages publics signalés de la ville courante
     */
    public function publicMessageAbuseAction() {
    
    	$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//paginations
    	$nb_entities = $publicMessages = $this->getDoctrine()->getRepository('CTRVFlowBundle:PublicMessage')->getMessagePublicAbuseNumber($city);
        $nb_entities_page = Constants::publicmessage_number_per_page;
        $nb_pages = ceil($nb_entities/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $publicMessages = $this->getDoctrine()->getRepository('CTRVFlowBundle:PublicMessage')->getMessagePublicAbuse($city, $offset, $nb_entities_page);
       	
       	return  array (
            'entities' => $publicMessages,
        	'nb_pages' => $nb_pages,
        	'page' => $page,
        	'nb_entities' => $nb_entities,
       			'city'=>$currentCity
        );
    }
    
    /**
     * Deletes an PublicMessage entity.
     *
     * @Route("/{id}/delete", name="publicmessage_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deleteAction($id) {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CTRVFlowBundle:PublicMessage')->find($id);

        $em->remove($entity);
        $em->flush();
        
        return new Response(json_encode(array('result'=>true)));
    }


}
