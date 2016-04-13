<?php
namespace Citroen\Service\GSA\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe SuggestRequest
 */
class SuggestRequest extends BaseModel
{

    protected $q;
    protected $max;
    protected $site;
    protected $client;
    protected $access;
    protected $format;

    /**
     *
     */
    public function __toRequest()
    {
        $aParams = array(
            'q' => $this->q,
            'max' => $this->max,
            'site' => $this->site,
            'client' => $this->client,
            'access' => $this->access,
            'format' => $this->format,
        );
        return $aParams;
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }

}