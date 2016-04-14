<?php
namespace PsaNdp\MappingBundle\Object\Block;

use PsaNdp\MappingBundle\Object\Content;

/**
 * Class Pc41MentionsJuridiques
 * @package PsaNdp\MappingBundle\Object\Block
 * @codeCoverageIgnore
 */
class Pc41MentionsJuridiques extends Content
{
    protected $mapping = array(
        'datalayer' => 'dataLayer'
    );

    /**
     * @var array
     */
    protected $mentions;

    /**
     * @param array $mentions
     * @return Pc41MentionsJuridiques
     */
    public function setMentions($mentions)
    {
        $this->mentions = $mentions;
        return $this;
    }

    /**
     * @return array
     */
    public function getMentions()
    {
        return $this->mentions;
    }
}
