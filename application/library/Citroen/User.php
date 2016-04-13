<?php

namespace Citroen;

/**
 * Class User
 */
class User
{

	/**
	 *
	 * @var type
	 */
	protected $id;

	/**
	 *
	 * @var type
	 */
	protected $firstname;

	/**
	 *
	 * @var type
	 */
	protected $lastname;

	/**
	 *
	 * @var type
	 */
	protected $civility;

	/**
	 *
	 * @var type
	 */
	protected $email;

	/**
	 *
	 * @var type
	 */
	protected $optinDealer = false;
	/**
	 *
	 * @var type
	 */
	protected $optinBrand = false;

	/**
	 *
	 * @var type
	 */
	protected $optinPartner = false;

	/**
	 *
	 * @var type
	 */
	protected $facebookId;

	/**
	 *
	 * @var type
	 */
	protected $facebookConnected = false;

	/**
	 *
	 * @var type
	 */
	protected $twitterId;

	/**
	 *
	 * @var type
	 */
	protected $twitterConnected = false;

	/**
	 *
	 * @var type
	 */
	protected $googleId;

	/**
	 *
	 * @var type
	 */
	protected $googleConnected = false;

	/**
	 *
	 * @var type
	 */
	protected $citroenId;

	/**
	 *
	 * @var type
	 */
	protected $isLogged = false;

	/**
	 *
	 */
	public function __construct($id)
	{
		$this->id = $id;
	}

	/**
	 *
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 *
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
	}

	/**
	 *
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 *
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
	}

	/**
	 *
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 *
	 */
	public function setCivility($civility)
	{
		$this->civility = $civility;
	}

	/**
	 *
	 */
	public function getCivility()
	{
		return $this->civility;
	}

	/**
	 *
	 */
	public function setEmail($email)
	{
		$this->email = $email;
	}

	/**
	 *
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 *
	 */
	public function setOptinDealer($optinDealer)
	{
		$this->optinDealer = $optinDealer;
	}

	/**
	 *
	 */
	public function getOptinDealer()
	{
		return $this->optinDealer;
	}

	/**
	 *
	 */
	public function setOptinBrand($optinBrand)
	{
		$this->optinBrand = $optinBrand;
	}

	/**
	 *
	 */
	public function getOptinBrand()
	{
		return $this->optinBrand;
	}

	/**
	 *
	 */
	public function setOptinPartner($optinPartner)
	{
		$this->optinPartner = $optinPartner;
	}

	/**
	 *
	 */
	public function getOptinPartner()
	{
		return $this->optinPartner;
	}

	/**
	 *
	 */
	public function setCitroenId($citroenId, $logged = true)
	{
		$this->citroenId = $citroenId;
		$this->isLogged = $logged;
	}

	/**
	 *
	 */
	public function getCitroenId()
	{
		return $this->citroenId;
	}

	/**
	 *
	 */
	public function setFacebookConnected()
	{
		$this->facebookConnected = true;
	}

	/**
	 *
	 */
	public function setFacebookId($facebookId)
	{
		$this->facebookId = $facebookId;
	}

	/**
	 *
	 */
	public function getFacebookId()
	{
		return $this->facebookId;
	}

	/**
	 *
	 */
	public function setTwitterConnected()
	{
		$this->twitterConnected = true;
	}

	/**
	 *
	 */
	public function setTwitterId($twitterId)
	{
		$this->twitterId = $twitterId;
	}

	/**
	 *
	 */
	public function getTwitterId()
	{
		return $this->twitterId;
	}

	/**
	 *
	 */
	public function setGoogleConnected()
	{
		$this->googleConnected = true;
	}

	/**
	 *
	 */
	public function setGoogleId($googleId)
	{
		$this->googleId = $googleId;
	}

	/**
	 *
	 */
	public function getGoogleId()
	{
		return $this->googleId;
	}

	/**
	 *
	 */
	public function isLogged()
	{
		return $this->isLogged;
	}

	/**
	 *
	 */
	public function isFacebookConnected()
	{
		return $this->facebookConnected;
	}

	/**
	 *
	 */
	public function isTwitterConnected()
	{
		return $this->twitterConnected;
	}

	/**
	 *
	 */
	public function isGoogleConnected()
	{
		return $this->googleConnected;
	}

}
