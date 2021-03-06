<?php

namespace CTRV\CommonBundle\Controller;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\HttpFoundation\Response;

use CTRV\CommonBundle\DependencyInjection\Constants;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use JMS\SecurityExtraBundle\Annotation\Secure;
use CTRV\CommonBundle\Entity\Abuse;
/**
 * Abuse controller.
 *
 * @Route("/abuse")
 */
class AbusController extends Controller
{
    
    /**
     * @Route("/list",name="abuse")
     * @Template()
     * Retourne la liste de tous les Abus 
     */
    public function allAbuseAction(){
    	
    	$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
    	
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	
    	$page = intval ($this->getRequest()->get("page",1));
    	//pagination
    	$nb_entities = $abuses = $this->getDoctrine()->getRepository('CTRVCommonBundle:Abuse')->getAllAbuseNumber($city);
    	$nb_entities_page = Constants::abuse_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    	 
    	$abusesPublicMessage = $this->getDoctrine()->getRepository("CTRVCommonBundle:Abuse")->getAllPublicMessageAbuses($city, $offset, $nb_entities_page);
    	$abusesComment = $this->getDoctrine()->getRepository("CTRVCommonBundle:Abuse")->getAllCommentAbuses($city, $offset, $nb_entities_page);
    	$abuses = $abusesPublicMessage;
    	$abuses = array_merge($abuses, $abusesComment );
    	
    	return  array (
    			'entities' => $abuses,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    } 
    
    /**
     * Supprimer un abus
     *
     * @Route("/{id}/delete", name="abuse_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deleteAction($id) {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVCommonBundle:Comment')->find($id);
    	
    	$em->remove($entity);
    	$em->flush();
    
    	return new Response(json_encode(array('result'=>true)));
    }
}
