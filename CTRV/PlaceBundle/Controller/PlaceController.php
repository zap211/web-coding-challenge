<?php

namespace CTRV\PlaceBundle\Controller;

use CTRV\PlaceBundle\Form\PlaceRechercheType;
use Symfony\Component\HttpFoundation\Response;
use CTRV\PlaceBundle\Form\ChoosePlaceTypeType;
use CTRV\CommonBundle\DependencyInjection\Constants;
use Doctrine\ORM\EntityRepository;
use CTRV\PlaceBundle\Form\ChoosePlaceTypeForm;
use CTRV\PlaceBundle\Form\PlaceForm;
use CTRV\PlaceBundle\Form\PlaceLatLongForm;
use CTRV\PlaceBundle\Form\PlaceDescriptionForm;
use CTRV\PlaceBundle\Form\PlaceTypeType;
use CTRV\PlaceBundle\Form\PlaceTypeModifType;
use CTRV\PlaceBundle\Form\ImportPlaceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\PlaceBundle\Entity\Place;
use CTRV\PlaceBundle\Entity\PlaceType;
use CTRV\PlaceBundle\Entity\ImportPlace;

/**
 * Place controller.
 *
 * @Route("/place")
 */
class PlaceController extends Controller
{
	
	/**
	 * @Route("/delete_double",name="delete_double")
	 * @Template()
	 */
	public function autoDeleteDoubleAction()
	{
		$city = null;
		if ($this->get('session_service')->getCity() == null) {
			$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->findOneBy(array());
			$this->get('session_service')->setCity($city);
		} else {
			$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($this->get('session_service')->getCity()->getId());
		}
	
		return array("entities"=>$this->get("place_service")->deleteDoublePlaces($city));
	}
	
	/**
	 * Formulaire de recherche d'une place
	 * @Route("/rechercher",name="rechercher_place")
	 * @Template()
	 */
	public function rechercherPlaceAction () {
		
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
		
		$localizedPlaceNumber =  $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getLocalizedPlacesByCityNumber($city);
		$form = $this->createForm(new PlaceRechercheType());
		
		return array('form'=>$form->createView(),
					'localizedPlaceNumber'=>$localizedPlaceNumber
				);
	}
	/**
	 * ajouter une nouvelle place
	 * @Route("/addNewPlace",name="addNew_place")
	 * @Template()
	 */
	public function addNewPlaceAction () {
		$em = $this->getDoctrine()->getEntityManager();
		$form = $this->createForm(new PlaceForm(),new Place());
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			$form->bind($this->getRequest());
			// On vérifie que les valeurs rentrées sont correctes
			if ($form->isValid()) {
				// On enregistre notre objet $placeType dans la base de données
				$place = $form->getData();
				$em->persist($place);
				$em->flush();
				// on redirige vers l'ajout des types de place
				return $this->redirect($this->generateUrl("place"));
			}
		}
	
			
		return array('form'=>$form->createView());
			
	}
	/**
	 * ajouter un  nouveau type de place
	 * @Route("/ajouter",name="ajouter_place")
	 * @Template()
	 */
	public function ajouterTypeAction () {
		$em = $this->getDoctrine()->getEntityManager();
		$form = $this->createForm(new PlaceTypeType(),new PlaceType());
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			$form->bind($this->getRequest());
			// On vérifie que les valeurs rentrées sont correctes
			if ($form->isValid()) {
				// On enregistre notre objet $placeType dans la base de données
				$placeType = $form->getData();
				$em->persist($placeType);
				$em->flush();
				// on redirige vers la liste des types de place
				return $this->redirect($this->generateUrl("loadTypePlaces"));			}
		}
	
					
		return array('form'=>$form->createView());
			
	}
	/**
	 * modifier un type de place
	 * @Route("/modifier/{id}",name="modifier_place")
	 * @Template()
	 */
	public function modifierTypeAction(PlaceType $placeType) {
		$form = $this->createForm(new PlaceTypeType, $placeType);
		$request=$this->getRequest();
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
		// On fait le lien Requête <-> Formulaire
		$form->bind($this->getRequest());
		// On vérifie que les valeurs rentrées sont correctes
		if ($form->isValid()) {
		// On enregistre notre objet $placeType dans la base de données
		$em = $this->getDoctrine()->getEntityManager();
		$em->persist($placeType);
		$em->flush();
		// on redirige vers les types de place
		return $this->redirect($this->generateUrl("loadTypePlaces"));
				}
			
		}
	
			
		return array(
				'placeType'=> $placeType,
				'form'=>$form->createView());
			
	}
	/**
	 * modifier une place
	 * @Route("/updatePlace/{id}",name="update_place")
	 * @Template()
	 */
	public function updatePlaceAction(Place $place) {
		$form = $this->createForm(new PlaceForm(), $place);
		$request=$this->getRequest();
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			$form->bind($this->getRequest());
			// On vérifie que les valeurs rentrées sont correctes
			if ($form->isValid()) {
				// On enregistre notre objet $place dans la base de données
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($place);
				$em->flush();
				// on redirige vers la liste des places existantes
				return $this->redirect($this->generateUrl("place"));
			}
				
		}
	
			
		return array(
				'place'=> $place,
				'form'=>$form->createView());
			
	}
	/**
	 * saisir latitude et longitude
	 * @Route("/saisir/{id}",name="saisir_lat_long")
	 * @Template()
	 */
	public function saisirLatLongAction(Place $place) {
		$form = $this->createForm(new PlaceLatLongForm(), $place);
		$request=$this->getRequest();
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			$form->bind($this->getRequest());
			// On vérifie que les valeurs rentrées sont correctes
			if ($form->isValid()) {
				// On enregistre notre objet $place dans la base de données
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($place);
				$em->flush();
				// on redirige vers la liste des places existantes
				return $this->redirect($this->generateUrl("placeWithoutLatLong"));
			}
	
		}
	
			
		return array(
				'place'=> $place,
				'form'=>$form->createView());
			
	}
	/**
	 * saisir Description
	 * @Route("/saisirDescrition/{id}",name="saisir_description")
	 * @Template()
	 */
	public function saisirDescriptionAction(Place $place) {
		$form = $this->createForm(new PlaceDescriptionForm(), $place);
		$request=$this->getRequest();
		// On vérifie qu'elle est de type POST
		if ($this->getRequest()->getMethod() == 'POST') {
			// On fait le lien Requête <-> Formulaire
			$form->bind($this->getRequest());
			// On vérifie que les valeurs rentrées sont correctes
			if ($form->isValid()) {
				// On enregistre notre objet $place dans la base de données
				$em = $this->getDoctrine()->getEntityManager();
				$em->persist($place);
				$em->flush();
				// on redirige vers la liste des places existantes
				return $this->redirect($this->generateUrl("placeWithoutDescription"));
			}
	
		}
	
			
		return array(
				'place'=> $place,
				'form'=>$form->createView());
			
	}
	
	/**
	 * Afficher le resultat de la recherche sur les places
	 * @Route("/rechercherResult",name="rechercher_place_result")
	 * @Template()
	 */
	public function rechercherPlaceResultAction () {
		
		$searchText = $this->getRequest()->get("searchText","");
		
		$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());

		$page = intval($this->getRequest()->get("page",1));
		//pagination
		$nb_entities = $places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlaceByStreetNumber($searchText, $city);
		$nb_entities_page = Constants::places_search_number_per_page;
		$nb_pages = ceil($nb_entities/$nb_entities_page);
		$offset = ($page-1) * $nb_entities_page;
		
		$places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlaceByStreet($searchText, $city, $offset, $nb_entities_page);
		
		return  array (
				'entities' => $places,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities
		);
	}
	
	
	/**
	 * Importer un fichier de données de place
	 * @Route("/import",name="import_place")
	 * @Template()
	 */
	public function importAction () {
	
		$form = $this->createForm(new ImportPlaceType(), new ImportPlace());
	
		if( $this->get('request')->getMethod() == 'POST' ) {
				
			$form->bind($this->get('request'));
	
			if ( $form->isValid() ) {
	
				$city = $this->get('session')->get("city");
				
				$importPlace = $form->getData();
				$fileName = uniqid($city->getName().$importPlace->placeType).'.'.$importPlace->file->guessExtension();
				$importPlace->file->move($importPlace->getUploadRootDir(), $fileName);
				$importPlace->file = null;
				
				$res = $this->get("place_service")->savePlaceDataFromFile ($importPlace->getUploadRootDir().'/'.$fileName, $city, $importPlace->placeType);
				
				$this->get('session')->getFlashBag()->add('success', $this->get('translator')
						->trans('place.import.success',array("%imported%"=>$res['added'],"%already%"=>$res["already"],"%traited%"=>$res['latLngFound'],"%total%"=>$res['all'])));
				
			}
		}
		
		$notLocalizedPlaceNumber =  $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getNotLocalizedPlaceNumber(); 
		$allPlaceNumber =  $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getAllPlaceNumber();
		//$localized = $allPlaceNumber - $notLocalizedPlaceNumber;
		
		return array('form'=>$form->createView(),'notLocalizedPlacesNumber'=>$notLocalizedPlaceNumber,'allPlaceNumber'=>$allPlaceNumber);
	}
	
	/**
	 * Lance le calcul de la latitude et longitude des places dont les coordonnées n'ont encore été trouvées
	 * @Route("/calculate_Lat_Lng", name="calculate_Lat_Lng")
     * @Template("CTRVPlaceBundle:Place:import.html.twig")
	 */
	public function calculateLatLngAction () {
		
		$form = $this->createForm(new ImportPlaceType(), new ImportPlace());
		
		$city = $this->get('session')->get("city");
		$res = $this->get("place_service")->calculateLatLng($city);
		$traitedPlaceNumber = $res[0];
		$totalPlaceNumber = $res[1];
		
		$this->get('session')->getFlashBag()->add('success', $this->get('translator')
				->trans('place.import.relaunch_success',array("%traited%"=>$traitedPlaceNumber,"%total%"=>$totalPlaceNumber)));
		
		$notLocalizedPlaceNumber =  $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getNotLocalizedPlaceNumber(); 
		$allPlaceNumber =  $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getAllPlaceNumber();
		//$localized = $allPlaceNumber - $notLocalizedPlaceNumber;
		
		return array('form'=>$form->createView(),'notLocalizedPlacesNumber'=>$notLocalizedPlaceNumber,'allPlaceNumber'=>$allPlaceNumber);
	}
	
	
    /**
     * Affiche le formulaire de choix de type de place 
     * @Route("/list", name="place")
     * @Template()
     */
    public function indexAction () {
    	
        $form = $this->createForm(new ChoosePlaceTypeType());
        return array (
        	'form' => $form->createView(),
        );
    }
    
    /**
     * Charge les donnees du type de place spécifié en AJAX (places ajoutées par des utilisateurs sous forme de tableau paginé)
     * @Route("/loadPlacesByType", name="loadPlacesByType")
     * @Template()
     */
    public function loadPlacesByTypeAction () {
    	
    	$currentCity = $this->get("session_service")->getCity();
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
    	    	
    	$page = intval($this->getRequest()->get("page",1));
    	$placeTypeId = $this->getRequest()->get("placeTypeId");
    	
    	//pagination
    	$nb_entities = $places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlaceAddedByUsersNumber($placeTypeId, $currentCity);
    	$nb_entities_page = Constants::places_number_per_page;
        $nb_pages = ceil($nb_entities/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlaceAddedByUsers ($placeTypeId, $currentCity, $offset, $nb_entities_page);
       	
       	return  array (
            'entities' => $places,
        	'nb_pages' => $nb_pages,
        	'page' => $page,
        	'nb_entities' => $nb_entities
        );
    }

    /**
     * Charge les donnees des places avec le type spécifié ou non (toutes les places de l'application)
     * @Route("/loadAllPlacesByTypeOrNot", name="loadAllPlacesByTypeOrNot")
     * @Template()
     */
    public function loadAllPlacesByTypeOrNotAction () {
    	 
    	$currentCity = $this->get("session_service")->getCity();
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
    	$form = $this->createForm(new ChoosePlaceTypeType());
    	$page = intval($this->getRequest()->get("page",1));
    	$placeTypeId = $this->getRequest()->get("placeTypeId");
    	 
    	//pagination
    	$nb_entities = $places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlacesByTypeNumber($placeTypeId, $currentCity);
    	$nb_entities_page = Constants::places_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:Place')->getPlacesByType ($placeTypeId, $currentCity, $offset, $nb_entities_page);
    
    	return  array (
    			'entities' => $places,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities,
    			'form' => $form->createView()
    	);
    }
    
    /**
     * Charge les données des places de la ville courante sans Latitude ou Longitude
     * @Route("/placeWithoutLatLong", name="placeWithoutLatLong")
     * @Template()
     */
    public function placeWithoutLatLongAction () {
    	 
    	$currentCity = $this->get('session_service')->getCity();
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	 
    	$page = $this->getRequest()->get("page",1);
    	 
    	//pagination
    	$nb_entities = $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getPlacesWithoutLatLongNumber($currentCity);
    	$nb_entities_page = Constants::places_without_lat_lng_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$entities = $this->get("place_service")->getPlacesWithoutLatLong($currentCity,$offset,$nb_entities_page);
    	
    	return  array (
    			'entities' => $entities,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    }
    
    /**
     * Charge les données des places de la ville courante sans Description
     * @Route("/placeWithoutDescription", name="placeWithoutDescription")
     * @Template()
     */
    public function placeWithoutDescriptionAction () {
    
    	$currentCity = $this->get('session_service')->getCity();
       	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    
    	$page = $this->getRequest()->get("page",1);
    
    	//pagination
    	$nb_entities = $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getPlacesWithoutDescriptionNumber($currentCity);
    	$nb_entities_page = Constants::places_without_description_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$entities = $this->get("place_service")->getPlacesWithoutDescription($currentCity,$offset,$nb_entities_page);
    
    	return  array (
    			'entities' => $entities,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    }
    /**
    * Charge tous les type de place 
    * @Route("/loadTypePlaces", name="loadTypePlaces")
    * @Template()
    */
    public function loadTypePlacesAction () {
    	 
    	$currentCity = $this->get("session_service")->getCity();
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	    	
    	$page = intval($this->getRequest()->get("page",1));
    	
    	//pagination
    	$nb_entities = $places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:PlaceType')->getTypePlacesNumber();
    	$nb_entities_page = Constants::places_type_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    
    	$places = $this->getDoctrine()->getRepository('CTRVPlaceBundle:PlaceType')->getTypePlaces($offset, $nb_entities_page);
    
    	return  array (
    			'entities' => $places,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    }
    

    /**
     * Deletes a Place entity.
     *
     * @Route("/{id}/delete", name="place_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deleteAction($id) {
    	
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('CTRVPlaceBundle:Place')->find(intval($id));
        $em->remove($entity);
        $em->flush();
        
        return new Response(json_encode(array('result'=>true)));
    }
    
    
    /**
     * Deletes a lit of Place entity.
     *
     * @Route("/deleteList", name="place_list_delete" ) 
     * @Template()
     */
    public function deleteListAction() {
    	 
    	$selectedIds = json_decode($this->getRequest()->get("selectedIds"));
    	$em = $this->getDoctrine()->getManager();
    	
    	if ($selectedIds != null)
    	foreach ($selectedIds as $id ) {
	    	$entity = $em->getRepository('CTRVPlaceBundle:Place')->find($id);
	    	$em->remove($entity);
	    	$em->flush();
    	}
    
    	return new Response(json_encode(array('result'=>true)));
    }
    
    /**
     * @Route("/placeWithSameAddress", name="placeWithSameAddress")
     * @Template()
     */
    public function placeWithSameAddressAction () {
    	
    	$currentCity = $this->get('session_service')->getCity();
    	
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    	
    	$page = $this->getRequest()->get("page",1);
    	
    	//pagination
    	$nb_entities = $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getPlacesHavinAddressInDoubleByCityNumber($currentCity->getId());
    	$nb_entities_page = Constants::places_with_same_address_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    	
    	$places = $this->getDoctrine()->getEntityManager()->getRepository("CTRVPlaceBundle:Place")->getPlacesHavinAddressInDoubleByCity($currentCity->getId(), $offset, $nb_entities_page);
    	
    	
//     	var_dump($places);exit;
    	return  array (
    			'entities' => $places,
    			'nb_pages' => $nb_pages,
    			'page' => $page,
    			'nb_entities' => $nb_entities
    	);
    }
    
    /**
     * Deletes a PlaceType entity.
     *
     * @Route("/{id}/deleteType", name="placeType_delete" ) //requirements={"id" = "\d+"}
     * @Method("POST")
     * @Template()
     */
    public function deletePlaceTypeAction($id) {
    	 
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVPlaceBundle:PlaceType')->find($id);
    
    	$em->remove($entity);
    	$em->flush();
    
    	return new Response(json_encode(array('result'=>true)));
    }

}
