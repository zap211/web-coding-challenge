<?php

namespace CTRV\FlowBundle\Service;

use CTRV\FlowBundle\Entity\GroupUser;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;

class GroupeService {
	
	protected $mailer;
	protected $doctrine;
	protected $em;
	protected $service_container;
	protected $templating;
	
	
	public function __construct(\Swift_Mailer $mailer,RegistryInterface $doctrine, ContainerInterface $service_container, TwigEngine $templating,TranslatorInterface $translator) {
		
		$this->mailer = $mailer;
		$this->doctrine = $doctrine;
		$this->em = $doctrine->getEntityManager();
		$this->service_container = $service_container;
		$this->templating = $templating;
		$this->translator = $translator;
	}
/**
 * retourne la liste de tous les groupes
 */	
	public function getGroup() {
		$entities = $this->em->getRepository('CTRVFlowBundle:GroupUser')->getGroup();
		return $entities;
	}
	/**
	 * retourne la liste des groupes de la ville courante
	 */
	public function getGroupByCity($currentCity) {
		$entities = $this->em->getRepository('CTRVFlowBundle:GroupUser')
		->getGroupByCity($this->em->getRepository('CTRVCommonBundle:City')->find($currentCity->getId()));
		return $entities;
	}
/**
 * retourne la liste des membres du groupe spécifié
 */	
	public function getGroupMember($group_user) {
		$entities = $this->em->getRepository('CTRVFlowBundle:GroupMember')->findByGroupUser($group_user);
		return $entities;
	}
}
