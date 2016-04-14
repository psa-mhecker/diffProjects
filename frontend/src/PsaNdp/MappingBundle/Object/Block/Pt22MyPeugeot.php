<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PSA\MigrationBundle\Entity\Page\PsaPageZoneConfigurableInterface;
use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pt22MyPeugeot
 * @codeCoverageIgnore
 */
class Pt22MyPeugeot extends Content
{
    protected $mapping = array(
        'datalayer' => 'dataLayer',
        'blockContent' => 'description'
    );

    /**
     * Content utilisé parce qu'on n'a que title et url
     * @var Content
     */
    protected $mainLinkUser;

    /**
     * @var Pt22ActionCompte
     */
    protected $signIn;

    /**
     * @var Pt22ActionCompte
     */
    protected $signUp;

    /**
     * TODO changer en string si la modification demandée à ISOBAR est acceptée
     * @var array
     */
    protected $description;


    protected $descriptionStoreApp = '';

    /**
     * @var array
     */
    protected $contentFooter;

    /**
     * @var array<Badge>
     */
    protected $appstores = array();


    /**
     * @return Content
     */
    public function getMainLinkUser()
    {
        return $this->mainLinkUser;
    }

    /**
     * @param Content $mainLinkUser
     * @return Pt22MyPeugeot
     */
    public function setMainLinkUser(Content $mainLinkUser)
    {
        $this->mainLinkUser = $mainLinkUser;
        return $this;
    }

    /**
     * @return Pt22ActionCompte
     */
    public function getSignIn()
    {
        return $this->signIn;
    }

    /**
     * @param Pt22ActionCompte $signIn
     * @return Pt22MyPeugeot
     */
    public function setSignIn(Pt22ActionCompte $signIn)
    {
        $this->signIn = $signIn;

        return $this;
    }

    /**
     * @return Pt22ActionCompte
     */
    public function getSignUp()
    {
        return $this->signUp;
    }

    /**
     * @param Pt22ActionCompte $signUp
     * @return Pt22MyPeugeot
     */
    public function setSignUp(Pt22ActionCompte $signUp)
    {
        $this->signUp = $signUp;

        return $this;
    }

    /**
     * @return array
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param array $description
     * @return Pt22MyPeugeot
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescriptionStoreApp()
    {
        return $this->descriptionStoreApp;
    }

    /**
     * @param string $descriptionStoreApp
     * @return Pt22MyPeugeot
     */
    public function setDescriptionStoreApp($descriptionStoreApp)
    {
        $this->descriptionStoreApp = $descriptionStoreApp;

        return $this;
    }

    /**
     * @return array
     */
    public function getAppstores()
    {
        return $this->appstores;
    }

    /**
     * @param array $appstores
     * @return Pt22MyPeugeot
     */
    public function setAppstores($appstores)
    {
        $this->appstores = $appstores;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getContentFooter()
    {
        $content = [];
        $content['background'] = $this->contentFooter;
        return $content;
    }

    /**
     * @param mixed $contentFooter
     *
     * @return Pt22MyPeugeot
     */
    public function setContentFooter($contentFooter)
    {
        $this->contentFooter = $contentFooter;

        return $this;
    }



}
