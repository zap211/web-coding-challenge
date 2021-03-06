<?php

namespace CTRV\CommonBundle\Entity;

use CTRV\CommonBundle\DependencyInjection\Constants;

use Doctrine\ORM\Mapping\OrderBy;

use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\Security\Core\User\UserProviderInterface;

use Doctrine\ORM\EntityRepository;

use Doctrine\ORM\Mapping as ORM;

class UserRepository extends EntityRepository implements UserProviderInterface {


	/**
	 * retourne la liste des utilisateurs de la ville courante avec leur rôle
	 * @param unknown_type $city
	 */
	public function getUsersByCity ($city, $first, $last) {
		
		$qb = $this->createQueryBuilder("p")
		->select('a.name, p.login,p.firstName,p.lastName, p.address, p.isActive,p.id, p.isBlocked')
		->from('CTRV\CommonBundle\Entity\Role','a')
		->where("p.city=?1")
		->andWhere("p.role=a.id")
		->orderBy('p.registrationDate', 'DESC')
		->setParameter(1, $city)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		return $qb->getQuery()->getResult();
	}

	/**
	 * Retourne le nombre total d'utilisateurs par ville
	 * @return multitype:
	 */
	public function getAllUsersNumberByCity ($city) {
	
		$qb = $this->createQueryBuilder("u")
		->select('count(u)')
		->where('u.city=?1')
		->setParameter(1, $city)
		;
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * Retourne le nombre total d'utilisateurs
	 * @return multitype:
	 */
	public function getAllUsersNumber () {

		$qb = $this->createQueryBuilder("p")
		->select('count(p)');
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserProviderInterface::loadUserByUsername()
	 */
	public function loadUserByUsername($username) {
		$q = $this
		->createQueryBuilder('u')
		->where('u.login = :login OR u.email = :email')
		->setParameter('login', $username)
		->setParameter('email', $username)
		->getQuery()
		;
	
		try {
			// The Query::getSingleResult() method throws an exception
			// if there is no record matching the criteria.
			$user = $q->getSingleResult();
		} catch (NoResultException $e) {
			$message = sprintf(
					'Unable to find an active admin AcmeUserBundle:User object identified by "%s".',
					$username
			);
			throw new UsernameNotFoundException($message, 0, $e);
		}
	
		return $user;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserProviderInterface::refreshUser()
	 */
	public function refreshUser(UserInterface $user)
	{
		$class = get_class($user);
		if (!$this->supportsClass($class)) {
			throw new UnsupportedUserException(
					sprintf(
							'Instances of "%s" are not supported.',
							$class
					)
			);
		}
	
		return $this->find($user->getId());
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserProviderInterface::supportsClass()
	 */
	public function supportsClass($class)
	{
		return $this->getEntityName() === $class
		|| is_subclass_of($class, $this->getEntityName());
	}

	/**
	 * Retourne la liste des users de nom ou prenom spécifié spécifiée
	 * @param unknown_type $searchText
	 */
	public function getUserByNameAndState($searchText, $etat, $city, $first, $last) {
		$qb = $this->createQueryBuilder("p")
		->select('a.name, p.login,p.firstName,p.lastName, p.address, p.isActive,p.id, p.isBlocked')
		->from('CTRV\CommonBundle\Entity\Role','a');
		
		if ($searchText!="") {
			$qb
			->where("p.firstName like ?1 OR p.lastName like ?1 OR p.login like ?1")
			->setParameter(1, "%".$searchText."%");
		} 
		
		$qb
		->andWhere("p.city=?2")
		->andWhere("p.role=a.id")
		->orderBy("p.login","ASC")
		->setParameter(2, $city)
		->setFirstResult($first)
		->setMaxResults($last)
		;
		
		if ($etat==Constants::STATE_USER_FILTER_BLOCKED) {
			$qb->andWhere("p.isBlocked=?3")
			->setParameter(3, true);
		}
		
		if ($etat==Constants::STATE_USER_FILTER_DISABED) {
			$qb->andWhere("p.isActive=?4")
			->setParameter(4, false);
		}
		
		return $qb->getQuery()->getResult();
	}
	
	/**
	 * Retourne le nombre d'utilisateurs de noms ou prénoms spécifié
	 * @param unknown_type $searchText
	 */
	public function getUserByNameAndStateNumber($searchText, $etat, $city) {
		$qb = $this->createQueryBuilder("p")
		->select('count(p)');
		
		if ($searchText!="") {
			$qb
			->where("p.firstName like ?1 OR p.lastName like ?1 OR p.login like ?1")
			->setParameter(1, "%".$searchText."%");
		}
		
		$qb
		->andWhere("p.city=?2")
		->setParameter(2, $city)
		;
		
		if ($etat==Constants::STATE_USER_FILTER_BLOCKED) {
			$qb->andWhere("p.isBlocked=?3")
			->setParameter(3, true);
		}
		
		if ($etat==Constants::STATE_USER_FILTER_DISABED) {
			$qb->andWhere("p.isActive=?4")
			->setParameter(4, false);
		}
		return $qb->getQuery()->getSingleScalarResult();
	}
}
