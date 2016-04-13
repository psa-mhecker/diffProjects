<?php
namespace Citroen\Service\BoutiqAcc\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe GetAccessoriesRequest
 */
class GetAccessoriesRequest extends BaseModel
{

    protected $clientId;
    protected $locale;
    protected $subUniverseCode;
    protected $bodyStyleCode;
    protected $modelCode;

    /**
     *
     */
    public function __toXML()
    {
        return "
        <ns1:getAccessories>
            <acc1:accessoriesInput xmlns:acc1=\"http://aoa.inetpsa.com/ws/setting/Accessories\">
                <acc1:valuedCriteria>
                    <acc1:valuedCriterion>
                        <acc1:locale>" . $this->locale . "</acc1:locale>
                        <acc1:subUniverseCode>" . $this->subUniverseCode . "</acc1:subUniverseCode>
                        <acc1:vehicle>
                            <exp:bodyStyleCode xmlns:exp=\"http://aoa.inetpsa.com/ws/export/ExportCriteriaValue\">" . $this->bodyStyleCode . "</exp:bodyStyleCode>
                            <exp:modelCode xmlns:exp=\"http://aoa.inetpsa.com/ws/export/ExportCriteriaValue\">" . $this->modelCode . "</exp:modelCode>
                        </acc1:vehicle>
                    </acc1:valuedCriterion>
                </acc1:valuedCriteria>
                <acc1:settings>
                    <crit:clientID xmlns:crit=\"http://aoa.inetpsa.com/ws/setting/Criteria\">" . $this->clientId . "</crit:clientID>
                    <crit:locales xmlns:crit=\"http://aoa.inetpsa.com/ws/setting/Criteria\">
                        <crit:locale>" . $this->locale . "</crit:locale>
                    </crit:locales>
                </acc1:settings>
            </acc1:accessoriesInput>
        </ns1:getAccessories>";
    }

    /**
     *
     */
    public function __toLog()
    {
        return ' Request : ';
    }
}