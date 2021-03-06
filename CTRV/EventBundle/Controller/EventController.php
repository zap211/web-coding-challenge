<?php

namespace CTRV\EventBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use CTRV\CommonBundle\DependencyInjection\Constants;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\EventBundle\Entity\Event;
use CTRV\EventBundle\Entity\UpdatedEvent;
use CTRV\EventBundle\Entity\EventType;
use CTRV\EventBundle\Form\EventTypeType;

/**
 * Event controller.
 *
 * @Route("/event")
 */
class EventController extends Controller
{
	/**
	 * Charge tous les événements en cours de la ville courante
	 * @Route("/listevent", name="event")
	 * @Template()
	 */
	public function loadEventAction () {
		 
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
    	$nb_entities = $events = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEventNumber($city);
    	$nb_entities_page = Constants::events_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$events = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEvent($city, $offset, $nb_entities_page);
    
    	return  array (
    			'entities' => $events,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities,
    			'city'=>$city
    	);
	
    
	}
	
	/**
	 * Charge tous les événements ayant des mis à jour proposés de la ville courante
	 * @Route("/eventUpdated", name="eventUpdated")
	 * @Template()
	 */
	public function loadEventUpdatedAction () {
			
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
	
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
		$nb_entities = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEventUpdatedByCityNumber($city);
		$nb_entities_page = Constants::events_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
		//On récupére l'ensemble des événements ayant des mis à jour proposés par ville
		$eventsUpdatedByCity = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEventUpdatedByCity($city, $offset, $nb_entities_page );
		
		// on crée un tableau
		$tab = array();
		// On récupére le nombre de mis à jour pour chaque event pour le placer dans le tableau
		foreach ($eventsUpdatedByCity as $entity) {
			$tab[$entity->getId()] = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getUpdatePerEventNumber($entity);
	
		}
		
		return  array (
				'update_per_event'=>$tab,
				'entities' => $eventsUpdatedByCity,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities,
				'city'=> $city
		);
	
	}
	
	/**
	 * Charge tous les mis à jour proposés de l'événement spécifié de la ville courante
	 * @Route("/updatePerEvent/{id}", name="updatePerEvent") //requirements={"id" = "\d+"}
	 * @Template()
	 */
	public function loadUpdatePerEventAction ($id) {
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('CTRVEventBundle:Event')->find($id);
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
	
		//pagination
		
		$nb_entities =  $this->getDoctrine()->getRepository('CTRVEventBundle:UpdatedEvent')->getUpdatePerEventNumber($entity, $city);
		$nb_entities_page = Constants::events_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
		$update_per_event = $this->getDoctrine()->getRepository('CTRVEventBundle:UpdatedEvent')->getUpdatePerEvent($entity,$city, $offset, $nb_entities_page);
		$currentEventWithUpdateProposed = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getcurrentEventWithUpdateProposed($entity ,$city, $offset, $nb_entities_page);
		
		return  array (
				'entities' => $update_per_event,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities,
				'events'=> $currentEventWithUpdateProposed,
				'city'=>$city
		);
	
	
	}
	
	/**
	 * Valider un mis à jour d'événement
	 *
	 * @Route("/validate/{id}", name="validateEventUpdated" ) //requirements={"id" = "\d+"}
	 * @Template()
	 */
	public function validateEventUpdatedAction ($id) {
		$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('CTRVEventBundle:UpdatedEvent')->find($id);
    		$em->persist($entity);
    		$em->flush();
		 
		return $this->redirect($this->generateUrl("eventUpdated"));
	}
	
	
	/**
	 * Charge tous les événements passés de la ville courante
	 * @Route("/eventPassed", name="eventPassed")
	 * @Template()
	 */
	public function loadEventPassedAction () {
			
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
	
		$page = intval ($this->getRequest()->get("page",1));
	
		//pagination
		$nb_entities = $events = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEventPassedNumber($city);
		$nb_entities_page = Constants::events_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
	
		$events = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getEventPassed($city, $offset, $nb_entities_page);
	
		return  array (
				'entities' => $events,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities,
				'city'=>$city
		);
	
	
	}
    	 
    	/**
    	 * Charge tous les agendas en cours de la ville courante
    	 * @Route("/listagenda", name="agenda")
    	 * @Template()
    	 */
    	public function loadAgendaAction () {
    		 
    		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		$date = date(gmdate("Y-m-d H:i:s"));
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
    	$nb_entities = $agendas = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getCurrentAgendaNumber($city, $date);
    	$nb_entities_page = Constants::agendas_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$agendas = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getCurrentAgenda($city, $offset, $nb_entities_page, $date);
    
    	return  array (
    			'entities' => $agendas,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities,
    			'city'=>$city
    	);
    	}
    	
    	
    	/**
    	 * Charge tous les agendas passés de la ville courante
    	 * @Route("/passedAgenda", name="passedAgenda")
    	 * @Template()
    	 */
    	public function loadPassedAgendaAction () {
    		 
    		$currentCity = $this->get("session_service")->getCity();
    		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
    	
    		if ($currentCity == null) {
    			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    			$this->redirect($this->generateUrl("home"));
    		}
    		$date = date(gmdate("Y-m-d H:i:s"));
    		$page = intval ($this->getRequest()->get("page",1));
    	
    		//pagination
    		$nb_entities = $agendas = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getPassedAgendaNumber($city, $date);
    		$nb_entities_page = Constants::agendas_number_per_page;
    		$nb_pages = ceil($nb_entities/$nb_entities_page);
    		$offset = ($page-1) * $nb_entities_page;
    	
    		$agendas = $this->getDoctrine()->getRepository('CTRVEventBundle:Event')->getPassedAgenda($city, $offset, $nb_entities_page, $date);
    	
    		return  array (
    				'entities' => $agendas,
    				'nb_pages' => $nb_pages,
    				'page' => $page,
    				'nb_entities' => $nb_entities,
    				'city'=>$city
    		);
    	}
    	
    	
    	/**
    	 * ajouter un type d'évenement
    	 * @Route("/ajouter",name="ajouter_evenement")
    	 * @Template()
    	 */
    	public function addEventTypeAction () {
    		$em = $this->getDoctrine()->getEntityManager();
    		$form = $this->createForm(new EventTypeType(),new EventType());
    		// On vérifie qu'elle est de type POST
    		if ($this->getRequest()->getMethod() == 'POST') {
    			// On fait le lien Requête <-> Formulaire
    			$form->bind($this->getRequest());
    			// On vérifie que les valeurs rentrées sont correctes
    			if ($form->isValid()) {
    				// On l'enregistre notre objet $placeType dans la base de données
    				$eventType = $form->getData();
    				$em->persist($eventType);
    				$em->flush();
    				return $this->redirect($this->generateUrl("loadTypeEvents"));
    			}
    		}
    	
    			
    		return array('form'=>$form->createView());
    			
    	}
    	/**
    	 * modifier un type d'evenment
    	 * @Route("/modifier/{id}",name="modifier_evenement")
    	 * @Template()
    	 */
    	public function updateEventTypeAction(EventType $eventType) {
    		$form = $this->createForm(new EventTypeType, $eventType);
    		$request=$this->getRequest();
    		// On vérifie qu'elle est de type POST
    		if ($this->getRequest()->getMethod() == 'POST') {
    			// On fait le lien Requête <-> Formulaire
    			$form->bind($this->getRequest());
    			// On vérifie que les valeurs rentrées sont correctes
    			if ($form->isValid()) {
    				// On enregistre notre objet $placeType dans la base de données
    				$em = $this->getDoctrine()->getEntityManager();
    				$em->persist($eventType);
    				$em->flush();
    				// on redirige vers les types de place
    				return $this->redirect($this->generateUrl("loadTypeEvents"));
    			}
    				
    		}
    	
    		return array(
    				'eventType'=> $eventType,
    				'form'=>$form->createView());
    			
    	}
    	/**
    	 * Charge les type d'évenement 
    	 * @Route("/loadTypeEvents", name="loadTypeEvents")
    	 * @Template()
    	 */
    	public function loadTypeEventsAction () {
    	
    		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
    	$nb_entities = $typeEvents = $this->getDoctrine()->getRepository('CTRVEventBundle:EventType')->getTypeEventsNumber();
    	$nb_entities_page = Constants::eventsComment_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$typeEvents = $this->getDoctrine()->getRepository('CTRVEventBundle:EventType')->getTypeEvents($offset, $nb_entities_page);
    
    	return  array (
    			'entities' => $typeEvents,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    	
   }
    	
    	 
    	
    	/**
    	 * Deletes an Event entity.
    	 *
    	 * @Route("/{id}/delete", name="event_delete" ) //requirements={"id" = "\d+"}
    	 * @Method("POST")
    	 * @Template()
    	 */
    	public function deleteAction($id) {
    		$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('CTRVEventBundle:Event')->find($id);
    	
    		$em->remove($entity);
    		$em->flush();
    	
    		return new Response(json_encode(array('result'=>true)));
    	}
    	
    	/**
    	 * Deletes an EventType entity.
    	 *
    	 * @Route("/{id}/deleteType", name="eventType_delete" ) //requirements={"id" = "\d+"}
    	 * @Method("POST")
    	 * @Template()
    	 */
    	public function deleteTypeAction($id) {
    		$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('CTRVEventBundle:EventType')->find($id);
    		 
    		$em->remove($entity);
    		$em->flush();
    		 
    		return new Response(json_encode(array('result'=>true)));
    	}

}
