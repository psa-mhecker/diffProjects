<?php

//namespace Service\BOForms\Model;

use Itkg\Service\Model as BaseModel;

/**
 * Classe UpdateInstanceRequest
 */
class Plugin_BOForms_Model_UpdateInstanceRequest extends BaseModel
{

	protected $instanceId;
	protected $instanceName;
	protected $formId;
	protected $formName;
	protected $formType;
	protected $instanceXML;
	protected $editable;
	protected $comment;
	
	/**
	 *
	 */
	/*public function __toRequest()
	{
		$aParams = array(
			'parameters' => urlencode(json_encode(
				array(
					'instanceId' => $this->instanceId,
					'instanceName' => $this->instanceName,
					'formId' => $this->formId,
					'formName' => $this->formName,
					'formType' => $this->formType,
					'instanceXML' => $this->instanceXML
				))
			)
		);
		return $aParams;
	}*/
	
	public function __toXML()
	{
		$return = "
	   <bof:updateInstance xmlns:bof='http://xml.inetpsa.com/Services/bend/BOFormService'>
         <bof1:updateInstanceRequest xmlns:bof1='http://com/inetpsa/dcr/xml/services/bend/boformservice'>
	        <bof1:userId>". $_SESSION[APP]['user']['id'] . "</bof1:userId>   
    	    <bof1:instanceId>".$this->instanceId."</bof1:instanceId>
            <bof1:instanceName>".$this->instanceName."</bof1:instanceName>
            <bof1:formId>".$this->formId."</bof1:formId>
            <bof1:formName>".$this->formName."</bof1:formName>
            <bof1:formType>".$this->formType."</bof1:formType>
            <bof1:instanceXML><![CDATA[".$this->instanceXML."]]></bof1:instanceXML>
        ";
		
		if(isset($this->editable))
		{
			$return .="<bof1:editable>".$this->editable."</bof1:editable>
					";
		}
		
		if(isset($this->comment))
		{
			$return .="<bof1:comment>".$this->comment."</bof1:comment>
					";
		}
		
		$return .="
         </bof1:updateInstanceRequest>
      </bof:updateInstance>";
		
		
		
		return $return;
		
	}

	/**
	 *
	 */
	public function __toLog()
	{
		return ' Request : ';
	}

}
