<?php
namespace Citroen\Service\BoutiqAcc\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetCriteriaValuesRequest
 */
class GetCriteriaValuesRequest extends BaseModel
{
    protected $clientId;
    protected $locale;
    protected $criterion;

    /**
     *
     */
    public function __toXML()
    {
        return "
        <ns1:getCriteriaValues>
            <crit:criteriaValueInput xmlns:crit=\"http://aoa.inetpsa.com/ws/setting/CriteriaValue\">
                <crit:criteria>
                    <crit:criterion>" . $this->criterion . "</crit:criterion>
                </crit:criteria>
                <crit:settings>
                    <crit1:clientID xmlns:crit1=\"http://aoa.inetpsa.com/ws/setting/Criteria\">" . $this->clientId . "</crit1:clientID>
                    <crit1:locales xmlns:crit1=\"http://aoa.inetpsa.com/ws/setting/Criteria\">
                        <crit1:locale>" . $this->locale . "</crit1:locale>
                    </crit1:locales>
                </crit:settings>
            </crit:criteriaValueInput>
        </ns1:getCriteriaValues>";
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}