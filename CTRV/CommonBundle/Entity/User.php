<?php

namespace CTRV\CommonBundle\Entity;
use Doctrine\ORM\Mapping\UniqueConstraint;

use Doctrine\ORM\Mapping\PrePersist;

use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

use Symfony\Component\Security\Core\User\UserInterface;

use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * CTRV\CommonBundle\Entity\User
 *
 * @ORM\Table(name="cityrovers_user",uniqueConstraints={@ORM\UniqueConstraint(name="useridUnique", columns={"userid"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CTRV\CommonBundle\Entity\UserRepository")
 * @HasLifecycleCallbacks()
 * @UniqueEntity(fields="login", message="user.register.form.login_already_exist")
 * @UniqueEntity(fields="email", message="user.register.form.email_already_exist")
 */
class User implements UserInterface, \Serializable/*, AdvancedUserInterface*/ {

	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="public_key", type="integer", nullable=false)
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;

	/**
	 * @var string $userid
	 *
	 * @ORM\Column(name="userid", type="string", length=255, nullable=false)
	 */
	private $userid;

	/**
	 * @var string $login
	 *
	 * @ORM\Column(name="login", type="string", length=256, nullable=false)
	 */
	private $login;

	/**
	 * @var string $salt
	 *
	 * @ORM\Column(name="salt", type="string", length=256, nullable=false)
	 */
	private $salt;

	/**
	 * @var string $password
	 *
	 * @ORM\Column(name="password", type="string", length=256, nullable=false)
	 */
	private $password;

	/**
	 * @var string $firstName
	 *
	 * @ORM\Column(name="first_name", type="string", length=256,nullable=true)
	 */
	private $firstName;

	/**
	 * @var string $lastName
	 *
	 * @ORM\Column(name="last_name", type="string", length=256,nullable=true)
	 */
	private $lastName;

	/**
	 * @var string $email
	 *
	 * @ORM\Column(name="email", type="string", length=256,nullable=false)
	 */
	private $email;

	/**
	 * @var string $registrationDate
	 *
	 * @ORM\Column(name="registration_date", type="datetime", nullable=false)
	 */
	private $registrationDate;

	/**
	 * @var string $latitude
	 *
	 * @ORM\Column(name="latitude", type="string", length=256, nullable=false)
	 */
	private $latitude;

	/**
	 * @var string $longitude
	 *
	 * @ORM\Column(name="longitude", type="string", length=256, nullable=false)
	 */
	private $longitude;

	/**
	 * @var string $isActive
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive = true;

	/**
	 * @var string $isActive
	 * @ORM\Column(name="is_blocked", type="boolean")
	 */
	private $isBlocked = false;
	
	/**
	 * @ORM\Column(name="state", type="string", length=256)
	 */
	private $state;

	/**
	 * @ORM\Column(name="last_login_date", type="datetime")
	 */
	private $lastLoginDate;
	
	/**
	 *  @ORM\Column(name="address", type="text", nullable=false)
	 * @var unknown_type
	 */
	private $address;

	/**
	 * @ORM\Column(name="language", type="string", length=256, nullable=true)
	 * @var unknown_type
	 */
	private $language;

	/**
	 * @var City
	 * @ORM\ManyToOne(targetEntity="City",inversedBy="users")
	 * @JoinColumn(name="city_id",referencedColumnName="id", onDelete="CASCADE")
	 */
	private $city;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\EventBundle\Entity\EventFollower",mappedBy="user")
	 */
	private $followedEvents;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\GroupUser",mappedBy="admin")
	 * @var unknown_type
	 */
	private $groupsAdmin; // liste des groupes administrés

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\GroupMember",mappedBy="member")
	 * @var unknown_type
	 */
	private $groupsMember; //liste des groupes ou le user est membre
	
	/**
	 * @var Role
	 * @ORM\ManyToOne(targetEntity="Role", inversedBy="users")
	 * @JoinColumn(name="role_id",referencedColumnName="id")
	 */
	private $role;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\PendingPrivateMessage",mappedBy="sender")
	 * @var unknown_type
	 */
	private $sentPendingsPrivateMessages;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\PendingPrivateMessage",mappedBy="receiver")
	 * @var unknown_type
	 */
	private $receivedPendingsPrivateMessages;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\PrivateMessage",mappedBy="sender")
	 * @var unknown_type
	 */
	private $sentPrivateMessages;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\PrivateMessage",mappedBy="receiver")
	 * @var unknown_type
	 */
	private $receivedPrivateMessages;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\FlowBundle\Entity\PrivateMessage",mappedBy="sender")
	 * @var unknown_type
	 */
	private $sentPublicMessages;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\EventBundle\Entity\Event",mappedBy="auteur")
	 * @var unknown_type
	 */
	private $events;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\EventBundle\Entity\UpdatedEvent",mappedBy="auteur")
	 * @var unknown_type
	 */
	private $updated_events;

	/**
	 * @ORM\OneToMany(targetEntity="\CTRV\PlaceBundle\Entity\Place",mappedBy="auteur")
	 * @var unknown_type
	 */
	private $places;

	/**
	 * @ORM\OneToMany(targetEntity="Contact",mappedBy="ownerUserid")
	 * @var unknown_type
	 */
	private $contacts;

	/**
	 * Liste des users dont on est le contact
	 * @ORM\OneToMany(targetEntity="Contact",mappedBy="contactPublicKey")
	 * @var unknown_type
	 */
	private $owners;

	/**
	 * @ORM\OneToMany(targetEntity="Comment",mappedBy="auteur")
	 * @var unknown_type
	 */
	private $comments;

	/**
	 * @ORM\OneToMany(targetEntity="Note",mappedBy="auteur")
	 * @var unknown_type
	 */
	private $notes;

	public function __toString() {
		return $this->login;
	}
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->followedEvents = new \Doctrine\Common\Collections\ArrayCollection();
		$this->groupsAdmin = new \Doctrine\Common\Collections\ArrayCollection();
		$this->groupsMember = new \Doctrine\Common\Collections\ArrayCollection();
		$this->sentPendingsPrivateMessages = new \Doctrine\Common\Collections\ArrayCollection();
		$this->receivedPendingsPrivateMessages = new \Doctrine\Common\Collections\ArrayCollection();
		$this->sentPrivateMessages = new \Doctrine\Common\Collections\ArrayCollection();
		$this->receivedPrivateMessages = new \Doctrine\Common\Collections\ArrayCollection();
		$this->sentPublicMessages = new \Doctrine\Common\Collections\ArrayCollection();
		$this->agendas = new \Doctrine\Common\Collections\ArrayCollection();
		$this->events = new \Doctrine\Common\Collections\ArrayCollection();
		$this->places = new \Doctrine\Common\Collections\ArrayCollection();
		$this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
		$this->owners = new \Doctrine\Common\Collections\ArrayCollection();
		$this->comments = new \Doctrine\Common\Collections\ArrayCollection();
		$this->notes = new \Doctrine\Common\Collections\ArrayCollection();
	}
	
	
	/**
	 * @PrePersist()
	 */
	public function setRegistrationiDateValue () {
		$this->registrationDate = new \DateTime();
	}

	/**
	 * Get id
	 *
	 * @return integer 
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * Set userid
	 *
	 * @param string $userid
	 * @return User
	 */
	public function setUserid($userid) {
		$this->userid = $userid;

		return $this;
	}

	public function setUsername($username) {
		$this->login = $username;
	
		return $this;
	}
	
	
	/**
	 * Get userid
	 *
	 * @return string 
	 */
	public function getUserid() {
		return $this->userid;
	}

	/**
	 * Set login
	 *
	 * @param string $login
	 * @return User
	 */
	public function setLogin($login) {
		$this->login = $login;

		return $this;
	}

	/**
	 * Get login
	 *
	 * @return string 
	 */
	public function getLogin() {
		return $this->login;
	}

	/**
	 * Set password
	 *
	 * @param string $password
	 * @return User
	 */
	public function setPassword($password) {
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password
	 *
	 * @return string 
	 */
	public function getPassword() {
		return $this->password;
	}

	/**
	 * Set firstName
	 *
	 * @param string $firstName
	 * @return User
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;

		return $this;
	}

	/**
	 * Get firstName
	 *
	 * @return string 
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * Set lastName
	 *
	 * @param string $lastName
	 * @return User
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;

		return $this;
	}

	/**
	 * Get lastName
	 *
	 * @return string 
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * Set email
	 *
	 * @param string $email
	 * @return User
	 */
	public function setEmail($email) {
		$this->email = $email;

		return $this;
	}

	/**
	 * Get email
	 *
	 * @return string 
	 */
	public function getEmail() {
		return $this->email;
	}

	/**
	 * Set registrationDate
	 *
	 * @param \DateTime $registrationDate
	 * @return User
	 */
	public function setRegistrationDate($registrationDate) {
		$this->registrationDate = $registrationDate;

		return $this;
	}

	/**
	 * Get registrationDate
	 *
	 * @return \DateTime 
	 */
	public function getRegistrationDate() {
		return $this->registrationDate;
	}

	/**
	 * Set latitude
	 *
	 * @param string $latitude
	 * @return User
	 */
	public function setLatitude($latitude) {
		$this->latitude = $latitude;

		return $this;
	}

	/**
	 * Get latitude
	 *
	 * @return string 
	 */
	public function getLatitude() {
		return $this->latitude;
	}

	/**
	 * Set longitude
	 *
	 * @param string $longitude
	 * @return User
	 */
	public function setLongitude($longitude) {
		$this->longitude = $longitude;

		return $this;
	}

	/**
	 * Get longitude
	 *
	 * @return string 
	 */
	public function getLongitude() {
		return $this->longitude;
	}

	/**
	 * Set isActive
	 *
	 * @param boolean $isActive
	 * @return User
	 */
	public function setIsActive($isActive) {
		$this->isActive = $isActive;

		return $this;
	}

	/**
	 * Get isActive
	 *
	 * @return boolean 
	 */
	public function getIsActive() {
		return $this->isActive;
	}

	/**
	 * Set isBlocked
	 *
	 * @param boolean $isBlocked
	 * @return User
	 */
	public function setIsBlocked($isBlocked) {
		$this->isBlocked = $isBlocked;

		return $this;
	}

	/**
	 * Get isBlocked
	 *
	 * @return boolean 
	 */
	public function getIsBlocked() {
		return $this->isBlocked;
	}

	/**
	 * Set city
	 *
	 * @param CTRV\CommonBundle\Entity\City $city
	 * @return User
	 */
	public function setCity(\CTRV\CommonBundle\Entity\City $city = null) {
		$this->city = $city;

		return $this;
	}

	/**
	 * Get city
	 *
	 * @return CTRV\CommonBundle\Entity\City 
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * Add followedEvents
	 *
	 * @param CTRV\EventBundle\Entity\EventFollower $followedEvents
	 * @return User
	 */
	public function addFollowedEvent(
			\CTRV\EventBundle\Entity\EventFollower $followedEvents) {
		$this->followedEvents[] = $followedEvents;

		return $this;
	}

	/**
	 * Remove followedEvents
	 *
	 * @param CTRV\EventBundle\Entity\EventFollower $followedEvents
	 */
	public function removeFollowedEvent(
			\CTRV\EventBundle\Entity\EventFollower $followedEvents) {
		$this->followedEvents->removeElement($followedEvents);
	}

	/**
	 * Get followedEvents
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getFollowedEvents() {
		return $this->followedEvents;
	}

	/**
	 * Add groupsAdmin
	 *
	 * @param CTRV\FlowBundle\Entity\GroupUser $groupsAdmin
	 * @return User
	 */
	public function addGroupsAdmin(
			\CTRV\FlowBundle\Entity\GroupUser $groupsAdmin) {
		$this->groupsAdmin[] = $groupsAdmin;

		return $this;
	}

	/**
	 * Remove groupsAdmin
	 *
	 * @param CTRV\FlowBundle\Entity\GroupUser $groupsAdmin
	 */
	public function removeGroupsAdmin(
			\CTRV\FlowBundle\Entity\GroupUser $groupsAdmin) {
		$this->groupsAdmin->removeElement($groupsAdmin);
	}

	/**
	 * Get groupsAdmin
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getGroupsAdmin() {
		return $this->groupsAdmin;
	}

	/**
	 * Add groupsMember
	 *
	 * @param CTRV\FlowBundle\Entity\GroupMember $groupsMember
	 * @return User
	 */
	public function addGroupsMember(
			\CTRV\FlowBundle\Entity\GroupMember $groupsMember) {
		$this->groupsMember[] = $groupsMember;

		return $this;
	}

	/**
	 * Remove groupsMember
	 *
	 * @param CTRV\FlowBundle\Entity\GroupMember $groupsMember
	 */
	public function removeGroupsMember(
			\CTRV\FlowBundle\Entity\GroupMember $groupsMember) {
		$this->groupsMember->removeElement($groupsMember);
	}

	/**
	 * Get groupsMember
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getGroupsMember() {
		return $this->groupsMember;
	}

	/**
	 * Add sentPendingsPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PendingPrivateMessage $sentPendingsPrivateMessages
	 * @return User
	 */
	public function addSentPendingsPrivateMessage(
			\CTRV\FlowBundle\Entity\PendingPrivateMessage $sentPendingsPrivateMessages) {
		$this->sentPendingsPrivateMessages[] = $sentPendingsPrivateMessages;

		return $this;
	}

	/**
	 * Remove sentPendingsPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PendingPrivateMessage $sentPendingsPrivateMessages
	 */
	public function removeSentPendingsPrivateMessage(
			\CTRV\FlowBundle\Entity\PendingPrivateMessage $sentPendingsPrivateMessages) {
		$this->sentPendingsPrivateMessages
				->removeElement($sentPendingsPrivateMessages);
	}

	/**
	 * Get sentPendingsPrivateMessages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getSentPendingsPrivateMessages() {
		return $this->sentPendingsPrivateMessages;
	}

	/**
	 * Add receivedPendingsPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PendingPrivateMessage $receivedPendingsPrivateMessages
	 * @return User
	 */
	public function addReceivedPendingsPrivateMessage(
			\CTRV\FlowBundle\Entity\PendingPrivateMessage $receivedPendingsPrivateMessages) {
		$this->receivedPendingsPrivateMessages[] = $receivedPendingsPrivateMessages;

		return $this;
	}

	/**
	 * Remove receivedPendingsPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PendingPrivateMessage $receivedPendingsPrivateMessages
	 */
	public function removeReceivedPendingsPrivateMessage(
			\CTRV\FlowBundle\Entity\PendingPrivateMessage $receivedPendingsPrivateMessages) {
		$this->receivedPendingsPrivateMessages
				->removeElement($receivedPendingsPrivateMessages);
	}

	/**
	 * Get receivedPendingsPrivateMessages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getReceivedPendingsPrivateMessages() {
		return $this->receivedPendingsPrivateMessages;
	}

	/**
	 * Add sentPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $sentPrivateMessages
	 * @return User
	 */
	public function addSentPrivateMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $sentPrivateMessages) {
		$this->sentPrivateMessages[] = $sentPrivateMessages;

		return $this;
	}

	/**
	 * Remove sentPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $sentPrivateMessages
	 */
	public function removeSentPrivateMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $sentPrivateMessages) {
		$this->sentPrivateMessages->removeElement($sentPrivateMessages);
	}

	/**
	 * Get sentPrivateMessages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getSentPrivateMessages() {
		return $this->sentPrivateMessages;
	}

	/**
	 * Add receivedPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $receivedPrivateMessages
	 * @return User
	 */
	public function addReceivedPrivateMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $receivedPrivateMessages) {
		$this->receivedPrivateMessages[] = $receivedPrivateMessages;

		return $this;
	}

	/**
	 * Remove receivedPrivateMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $receivedPrivateMessages
	 */
	public function removeReceivedPrivateMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $receivedPrivateMessages) {
		$this->receivedPrivateMessages->removeElement($receivedPrivateMessages);
	}

	/**
	 * Get receivedPrivateMessages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getReceivedPrivateMessages() {
		return $this->receivedPrivateMessages;
	}

	/**
	 * Add sentPublicMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $sentPublicMessages
	 * @return User
	 */
	public function addSentPublicMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $sentPublicMessages) {
		$this->sentPublicMessages[] = $sentPublicMessages;

		return $this;
	}

	/**
	 * Remove sentPublicMessages
	 *
	 * @param CTRV\FlowBundle\Entity\PrivateMessage $sentPublicMessages
	 */
	public function removeSentPublicMessage(
			\CTRV\FlowBundle\Entity\PrivateMessage $sentPublicMessages) {
		$this->sentPublicMessages->removeElement($sentPublicMessages);
	}

	/**
	 * Get sentPublicMessages
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getSentPublicMessages() {
		return $this->sentPublicMessages;
	}


	/**
	 * Add events
	 *
	 * @param CTRV\EventBundle\Entity\Event $events
	 * @return User
	 */
	public function addEvent(\CTRV\EventBundle\Entity\Event $events) {
		$this->events[] = $events;

		return $this;
	}

	/**
	 * Remove events
	 *
	 * @param CTRV\EventBundle\Entity\Event $events
	 */
	public function removeEvent(\CTRV\EventBundle\Entity\Event $events) {
		$this->events->removeElement($events);
	}

	/**
	 * Get events
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getEvents() {
		return $this->events;
	}

	/**
	 * Add places
	 *
	 * @param CTRV\PlaceBundle\Entity\Place $places
	 * @return User
	 */
	public function addPlace(\CTRV\PlaceBundle\Entity\Place $places) {
		$this->places[] = $places;

		return $this;
	}

	/**
	 * Remove places
	 *
	 * @param CTRV\PlaceBundle\Entity\Place $places
	 */
	public function removePlace(\CTRV\PlaceBundle\Entity\Place $places) {
		$this->places->removeElement($places);
	}

	/**
	 * Get places
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getPlaces() {
		return $this->places;
	}

	/**
	 * Add contacts
	 *
	 * @param CTRV\CommonBundle\Entity\Contact $contacts
	 * @return User
	 */
	public function addContact(\CTRV\CommonBundle\Entity\Contact $contacts) {
		$this->contacts[] = $contacts;

		return $this;
	}

	/**
	 * Remove contacts
	 *
	 * @param CTRV\CommonBundle\Entity\Contact $contacts
	 */
	public function removeContact(\CTRV\CommonBundle\Entity\Contact $contacts) {
		$this->contacts->removeElement($contacts);
	}

	/**
	 * Get contacts
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getContacts() {
		return $this->contacts;
	}

	/**
	 * Add owners
	 *
	 * @param CTRV\CommonBundle\Entity\Contact $owners
	 * @return User
	 */
	public function addOwner(\CTRV\CommonBundle\Entity\Contact $owners) {
		$this->owners[] = $owners;

		return $this;
	}

	/**
	 * Remove owners
	 *
	 * @param CTRV\CommonBundle\Entity\Contact $owners
	 */
	public function removeOwner(\CTRV\CommonBundle\Entity\Contact $owners) {
		$this->owners->removeElement($owners);
	}

	/**
	 * Get owners
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getOwners() {
		return $this->owners;
	}

	/**
	 * Add comments
	 *
	 * @param CTRV\CommonBundle\Entity\Comment $comments
	 * @return User
	 */
	public function addComment(\CTRV\CommonBundle\Entity\Comment $comments) {
		$this->comments[] = $comments;

		return $this;
	}

	/**
	 * Remove comments
	 *
	 * @param CTRV\CommonBundle\Entity\Comment $comments
	 */
	public function removeComment(\CTRV\CommonBundle\Entity\Comment $comments) {
		$this->comments->removeElement($comments);
	}

	/**
	 * Get comments
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getComments() {
		return $this->comments;
	}

	/**
	 * Add notes
	 *
	 * @param CTRV\CommonBundle\Entity\Note $notes
	 * @return User
	 */
	public function addNote(\CTRV\CommonBundle\Entity\Note $notes) {
		$this->notes[] = $notes;

		return $this;
	}

	/**
	 * Remove notes
	 *
	 * @param CTRV\CommonBundle\Entity\Note $notes
	 */
	public function removeNote(\CTRV\CommonBundle\Entity\Note $notes) {
		$this->notes->removeElement($notes);
	}

	/**
	 * Get notes
	 *
	 * @return Doctrine\Common\Collections\Collection 
	 */
	public function getNotes() {
		return $this->notes;
	}

	/**
	 * Set address
	 *
	 * @param string $address
	 * @return User
	 */
	public function setAddress($address) {
		$this->address = $address;

		return $this;
	}

	/**
	 * Get address
	 *
	 * @return string 
	 */
	public function getAddress() {
		return $this->address;
	}

	/**
	 * Set salt
	 *
	 * @param string $salt
	 * @return User
	 */
	public function setSalt($salt) {
		$this->salt = $salt;

		return $this;
	}

	/**
	 * Get salt
	 *
	 * @return string 
	 */
	public function getSalt() {
		return $this->salt;
	}

	/**
	 * Set numeroRue
	 *
	 * @param integer $numeroRue
	 * @return User
	 */
	public function setNumeroRue($numeroRue) {
		$this->numeroRue = $numeroRue;

		return $this;
	}

	/**
	 * Get numeroRue
	 *
	 * @return integer 
	 */
	public function getNumeroRue() {
		return $this->numeroRue;
	}
	
	/**
	 * 
	 */
	public function serialize()
	{
		return serialize(array(
				$this->id,//$this->login,$this->password,$this->email,
		));
	}
	
	/**
	 * @see \Serializable::unserialize()
	 */
	public function unserialize($serialized)
	{
		list (
				$this->id,//$this->login,$this->password,$this->email,
		) = unserialize($serialized);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserInterface::getRoles()
	 */
	public function getRoles() {
		
		$roles = array();
		array_push($roles, $this->getRole());
		return $roles;

	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserInterface::getUsername()
	 */
	public function getUsername() {
		return $this->login;

	}
	
	/**
	 * (non-PHPdoc)
	 * @see Symfony\Component\Security\Core\User.UserInterface::eraseCredentials()
	 */
	public function eraseCredentials() {
		// TODO: Auto-generated method stub

	}
	
	/**
	 * 
	 */
	public function isAccountNonExpired()
	{
		return true;
	}
	
	public function isEqualTo(UserInterface $user)
	{
		return $this->login === $user->getLogin();
	}
	
	/**
	 * 
	 */
	public function isAccountNonLocked()
	{
		return true;//return !$this->isBlocked;
	}
	
	public function isCredentialsNonExpired()
	{
		return true;
	}
	
	public function isEnabled()
	{
		return true;
	}
	
// 	public function isEqualTo(UserInterface $user)
// 	{
// 		return $this->login === $user->getLogin();
// 	}


    /**
     * Set language
     *
     * @param string $language
     * @return User
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    
        return $this;
    }

    /**
     * Get language
     *
     * @return string 
     */
    public function getLanguage()
    {
        return $this->language;
    }

   
    /**
     * Add updated_events
     *
     * @param CTRV\EventBundle\Entity\UpdatedEvent $updatedEvents
     * @return User
     */
    public function addUpdatedEvent(\CTRV\EventBundle\Entity\UpdatedEvent $updatedEvents)
    {
        $this->updated_events[] = $updatedEvents;
    
        return $this;
    }

    /**
     * Remove updated_events
     *
     * @param CTRV\EventBundle\Entity\UpdatedEvent $updatedEvents
     */
    public function removeUpdatedEvent(\CTRV\EventBundle\Entity\UpdatedEvent $updatedEvents)
    {
        $this->updated_events->removeElement($updatedEvents);
    }

    /**
     * Get updated_events
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUpdatedEvents()
    {
        return $this->updated_events;
    }

    /**
     * Set role
     *
     * @param CTRV\CommonBundle\Entity\Role $role
     * @return User
     */
    public function setRole(\CTRV\CommonBundle\Entity\Role $role = null)
    {
        $this->role = $role;
    
        return $this;
    }

    /**
     * Get role
     *
     * @return CTRV\CommonBundle\Entity\Role 
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Set state
     *
     * @param string $state
     * @return User
     */
    public function setState($state)
    {
        $this->state = $state;
    
        return $this;
    }

    /**
     * Get state
     *
     * @return string 
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set lastLoginDate
     *
     * @param \DateTime $lastLoginDate
     * @return User
     */
    public function setLastLoginDate($lastLoginDate)
    {
        $this->lastLoginDate = $lastLoginDate;
    
        return $this;
    }

    /**
     * Get lastLoginDate
     *
     * @return \DateTime 
     */
    public function getLastLoginDate()
    {
        return $this->lastLoginDate;
    }
}