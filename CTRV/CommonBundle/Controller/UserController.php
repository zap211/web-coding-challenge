<?php

namespace CTRV\CommonBundle\Controller;

use CTRV\CommonBundle\Form\RegistrationType;

use Symfony\Component\Security\Core\SecurityContext;

use CTRV\CommonBundle\Form\UserAuthType;

use Symfony\Component\HttpFoundation\Response;

use CTRV\CommonBundle\DependencyInjection\Constants;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use CTRV\CommonBundle\Entity\User;
use JMS\SecurityExtraBundle\Annotation\Secure;
use CTRV\CommonBundle\Form\UserRechercheType;
use CTRV\CommonBundle\Form\UserType;

/**
 * User controller.
 *
 * @Route("/utilisateur")
 */
class UserController extends Controller
{
	
	/**
     * Charge les données de tous les utilisateurs de la ville courante
     * @Route("/list", name="utilisateur")
     * @Template()
     */
     public function loadUserAction () {
    	
    	$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		$form = $this->createForm(new UserRechercheType(),array());
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
		$nb_entities = $users = $this->getDoctrine()->getRepository('CTRVCommonBundle:User')->getAllUsersNumberByCity($city);
        $nb_entities_page = Constants::users_number_per_page;
        $nb_pages = ceil($nb_entities/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $users = $this->getDoctrine()->getRepository('CTRVCommonBundle:User')->getUsersByCity($city, $offset, $nb_entities_page);
       	
       	return  array (
            'entities' => $users,
        	'nb_pages' => $nb_pages,
        	'page' => $page,
        	'nb_entities' => $nb_entities,
       			'city'=>$currentCity,
       			'users' => $users,
       			'form' => $form->createView()
        );
    }  
    
    /**
     * Charge les données de l'ensemble  des utilisateurs Connectés 
     * @Route("/connected", name="userConnectedByCity")
     * @Template()
     */
    public function loadUserConnectedByCityAction () {
    	 
    	$currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		$page = intval ($this->getRequest()->get("page",1));
		
		//pagination
		$nb_entities = $users = $this->getDoctrine()->getRepository('CTRVCommonBundle:ConnectedUsers')->getUsersConnectedNumberByCity($city);
        $nb_entities_page = Constants::users_number_per_page;
        $nb_pages = ceil($nb_entities/$nb_entities_page);
        $offset = ($page-1) * $nb_entities_page;
        
        $users = $this->getDoctrine()->getRepository('CTRVCommonBundle:ConnectedUsers')->getUsersConnectedByCity($city, $offset, $nb_entities_page);
       	
       	return  array (
            'entities' => $users,
        	'nb_pages' => $nb_pages,
        	'page' => $page,
        	'nb_entities' => $nb_entities,
       			'city'=>$currentCity,
       			'users' => $users
        );
    }

    /**
     * Charge les données de l'ensemble des utilisateurs du Systéme
     * @Route("/all", name="userAll")
     * @Template()
     */
    public function statsUserAction () {
    
    	$em = $this->getDoctrine()->getEntityManager();
        $currentCity = $this->get('session_service')->getCity();
    
    	if ($currentCity == null) {
    		$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
    		$this->redirect($this->generateUrl("home"));
    	}
    
    	// Calcul du nombre d'utilisateurs récupérés pour chaque fonction
    	$nb_users = $this->get("user_service")->getAllUsersNumber();
    	$nb_users_by_city = $this->get("user_service")->getAllUsersNumberByCity($currentCity);
    	$nb_connected_users = $this->get("user_service")->getUsersConnectedNumber();
    	$nb_connected_users_by_city = $this->get("user_service")->getUsersConnectedNumberByCity($currentCity);
    	
    	return  array (
    			'nb_users' => $nb_users,
    			'nb_users_by_city' => $nb_users_by_city,
    			'nb_connected_users' => $nb_connected_users,
    			'nb_connected_users_by_city' => $nb_connected_users_by_city
    	);
    }
    
    /**
     * Activer un utilisateur
     *
     * @Route("/active/{id}", name="user_active" ) //requirements={"id" = "\d+"}
     * @Template()
     */
    public function activeAction (User $id) {
    	$em = $this->getDoctrine()->getManager();
    		$entity = $em->getRepository('CTRVCommonBundle:User')->find($id);
    		$entity->setIsActive("1");
    		$em->persist($entity);
    		$em->flush();
    	
    	return $this->redirect($this->generateUrl("utilisateur"));
    }
    
    /**
     * Desactiver un utilisateur
     *
     * @Route("/desactive/{id}", name="user_desactive" ) //requirements={"id" = "\d+"}
     * @Template()
     */
     public function desactiveAction (User $id) {
		$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVCommonBundle:User')->find($id);
    	$entity->setIsActive("0");
    	$em->persist($entity);
    	$em->flush();
	
    	return $this->redirect($this->generateUrl("utilisateur"));
    }
    /**
     * Bloquer un utilisateur
     *
     * @Route("/block/{id}", name="user_block" ) //requirements={"id" = "\d+"}
     * @Template()
     */
    public function blockAction (User $id) {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVCommonBundle:User')->find($id);
    	$entity->setisBlocked("1");
    	$em->persist($entity);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl("utilisateur"));
    }
    /**
     * Debloquer un utilisateur
     *
     * @Route("/deblock/{id}", name="user_deblock" ) //requirements={"id" = "\d+"}
     * @Template()
     */
    public function deblockAction (User $id) {
    	$em = $this->getDoctrine()->getManager();
    	$entity = $em->getRepository('CTRVCommonBundle:User')->find($id);
    	$entity->setisBlocked("0");
    	$em->persist($entity);
    	$em->flush();
    
    	return $this->redirect($this->generateUrl("utilisateur"));
    }
    
    /**
     * Resultat de la recherche d'utilisateur
     * @Route("/rechercherResult",name="rechercher_user_result")
     * @Template()
     */
    public function rechercherUserResultAction () {
    
    	$searchText = $this->getRequest()->get("searchText","");
    	$etat = $this->getRequest()->get("etat","");
        $currentCity = $this->get("session_service")->getCity();
		$city = $this->getDoctrine()->getEntityManager()->getRepository('CTRVCommonBundle:City')->find($currentCity->getId());
	
		if ($currentCity == null) {
			$this->get('session')->getFlashBag()->add('error', $this->get('translator')->trans('session.city.not_found'));
			$this->redirect($this->generateUrl("home"));
		}
		
    	$page = intval($this->getRequest()->get("page",1));
    	
    	//pagination
    	$nb_entities = $users = $this->getDoctrine()->getRepository('CTRVCommonBundle:User')->getUserByNameAndStateNumber($searchText,$etat, $city);
    	$nb_entities_page = Constants::users_search_number_per_page;
    	$nb_pages = ceil($nb_entities/$nb_entities_page);
    	$offset = ($page-1) * $nb_entities_page;
    	
    	$users = $this->getDoctrine()->getRepository('CTRVCommonBundle:User')->getUserByNameAndState($searchText,$etat, $city, $offset, $nb_entities_page);
    
    	return  array (
				'entities' => $users,
				'nb_pages' => $nb_pages,
				'page' => $page,
				'nb_entities' => $nb_entities
    			);
    }
    
    /**
     * modifier un utilisateur
     * @Route("/userEdit/{id}",name="user_edit")
     * @Template()
     */
    public function userEditAction(User $user) {
    	$form = $this->createForm(new UserType(), $user);
    	$request=$this->getRequest();
    	// On vérifie qu'elle est de type POST
    	if ($this->getRequest()->getMethod() == 'POST') {
    		// On fait le lien Requête <-> Formulaire
    		$form->bind($this->getRequest());
    		// On vérifie que les valeurs rentrées sont correctes
    		if ($form->isValid()) {
    			// On enregistre notre objet $user dans la base de données
    			$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($user);
    			$em->flush();
    			// on redirige vers la liste des places existantes
    			return $this->redirect($this->generateUrl("utilisateur"));
    		}
    
    	}
    
    		
    	return array(
    			'user'=> $user,
    			'form'=>$form->createView());
    		
    }
    

}
