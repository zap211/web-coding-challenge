<?php

namespace CTRV\CommonBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use CTRV\CommonBundle\DependencyInjection\Constants;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\CommonBundle\Entity\Comment;
use CTRV\EventBundle\Entity\Event;
use CTRV\EventBundle\Entity\Agenda;
use CTRV\PlaceBundle\Entity\Place;


/**
 * Comment controller.
 *
 * @Route("/comment")
 */
class CommentController extends Controller
{  

	/**
	 * @Route("/eventCommentAbuse",name="eventCommentAbuse")
	 * @Template()
	 * Retourne la liste de tous les commentaires signalés portant sur les évenements de la ville courante
	 */
	public function eventCommentAbuseAction() {
	
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
		$nb_entities = $this->getDoctrine()->getRepository('CTRVCommonBundle:Comment')->getEventCommentAbuseNumber($city);
		$nb_entities_page = Constants::abuse_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
	
		$comments = $this->getDoctrine()->getRepository('CTRVCommonBundle:Comment')->getEventCommentAbuse($city, $offset, $nb_entities_page);
	
		return  array (
				'entities' => $comments,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities,
				'city'=>$currentCity
		);
	}
	
	/**
	 * @Route("/placeCommentAbuse",name="placeCommentAbuse")
	 * @Template()
	 * Retourne la liste de tous les commentaires signalés portant sur les places de la ville courante
	 */
	public function placeCommentAbuseAction(){
	
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
		$nb_entities = $comments = $this->getDoctrine()->getRepository('CTRVCommonBundle:Comment')->getPlaceCommentAbuseNumber($city);
		$nb_entities_page = Constants::abuse_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
	
		$comments = $this->getDoctrine()->getRepository('CTRVCommonBundle:Comment')->getPlaceCommentAbuse($city, $offset, $nb_entities_page);
	
		return  array (
				'entities' => $comments,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities,
				'city'=>$currentCity
		);
	}
	
    /**
     * Supprimer un commentaire
     *
     * @Route("/{id}/delete", name="comment_delete" ) //requirements={"id" = "\d+"}
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
