<?php

namespace CTRV\CommonBundle\Service;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CommonService {
	
	protected $mailer;
	protected $doctrine;
	protected $em;
	protected $service_container;
	protected $templating;
	protected $router;
	
	
	public function __construct(\Swift_Mailer $mailer,RegistryInterface $doctrine, ContainerInterface $service_container, TwigEngine $templating,TranslatorInterface $translator,\Symfony\Bundle\FrameworkBundle\Routing\Router $router) {
		
		$this->mailer = $mailer;
		$this->doctrine = $doctrine;
		$this->em = $doctrine->getEntityManager();
		$this->service_container = $service_container;
		$this->templating = $templating;
		$this->translator = $translator;
		$this->router = $router;
	}
	
}
