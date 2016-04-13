<?php
namespace Citroen\Service\GSA\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GeoLocalizeRequest
 */
class SearchRequest extends BaseModel
{

    protected $q;
    protected $client;
    protected $output;
    protected $sort;
    protected $site;
    protected $start;
    protected $num;
    protected $tlen;
	protected $oe;

    /**
     *
     */
    public function __toRequest()
    {
        $aParams = array(
            'q' => $this->q,
            'client' => $this->client,
            'output' => $this->output,
            'sort' => $this->sort,
            'site' => $this->site,
            'start' => $this->start,
            'num' => $this->num,
            'tlen' => $this->tlen,
            'filter' => 0,
			'oe'=>$this->oe
        );
		//AMOINEAYARI
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