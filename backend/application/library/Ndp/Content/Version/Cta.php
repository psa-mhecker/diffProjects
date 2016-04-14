<?php
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Cta.php';
include_once Pelican::$config['APPLICATION_LIBRARY'].'/Ndp/Content/Version/Cta/Interface.php';

/**
 * Gestion des page Content zone cta.
 *
 * @author Pierre Pottié <pierre.pottie@businessdecision.com>
 *
 * @since 23/04/2015
 */
class Ndp_Content_Version_Cta extends Ndp_Cta
{

    const CONTENT_ID_NEW = '-2';

    private $contentId;
    private $contentVersion;

    /**
     *
     * @return integer
     */
    public function getContentId()
    {

        return $this->contentId;
    }

    /**
     *
     * @param integer
     *
     * @return \Ndp_Content_Version_Cta
     */
    public function setContentId($contentId)
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     *
     * @return integer
     */
    public function getContentVersion()
    {

        return $this->contentVersion;
    }

    /**
     *
     * @param integer
     *
     * @return \Ndp_Content_Version_Cta
     */
    public function setContentVersion($contentVersion)
    {
        $this->contentVersion = $contentVersion;

        return $this;
    }

    /**
     *
     * @param array $values
     *
     * @return \Ndp_Content_Version_Cta
     */
    public function hydrate(array $values)
    {
        parent::hydrate($values);
        if (!empty($values['CONTENT_ID'])) {
            $this->setContentId($values['CONTENT_ID']);
        }

        if (!empty($values['CONTENT_VERSION'])) {
            $this->setContentVersion($values['CONTENT_VERSION']);
        }
        return $this;
    }
}
