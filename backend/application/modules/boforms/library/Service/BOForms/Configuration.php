<?php
//namespace Plugin\BOForms;

use Itkg\Service\Configuration as BaseConfiguration;

/**
 * Classe Configuration pour le service Instance
 */
class Plugin_BOForms_Configuration extends BaseConfiguration
{

    /**
     * Initialisation des models et des logs
     */
    public function __construct()
    {
        $this->identifier = '[CITROEN] - [BOFORMS]';
        $this->methodIdentifiers = array(
            'getInstances' => 'getInstances',
        	'getInstancesTest' => 'getInstancesTest',
            'getInstanceById' => 'getInstanceById',
        	'getInstanceByIdTest' => 'getInstanceByIdTest',
            'updateInstance' => 'updateInstance',
        	'duplicateInstance' => 'duplicateInstance',
            'getReferential' => 'getReferential',
        	'getReferentialTest' => 'getReferentialTest',
	    	'getReporting' => 'getReporting',
	    	'getLeadsByType' => 'getLeadsByType',
        	'getParameters' => 'getParameters',
        	'updateParameters' => 'updateParameters',
        	'getMasters' => 'getMasters',
        	'getInstancesByMaster' => 'getInstancesByMaster',
        	'deleteABTestingInstance' => 'deleteABTestingInstance' 
        );
        $this->models = array(
            'getInstances' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesResponse',
                    'mapping' => array(
                        'getInstancesResponse' => 'Plugin_BOForms_Model_GetInstancesResponse'
                    )
                )
            ),
        	'getInstancesTest' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesResponse',
                    'mapping' => array(
                        'getInstancesResponse' => 'Plugin_BOForms_Model_GetInstancesResponse'
                    )
                )
            ),
            'getInstanceById' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstanceByIdRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstanceByIdResponse',
                    'mapping' => array(
                        'getInstanceByIdResponse' => 'Plugin_BOForms_Model_GetInstanceByIdResponse'
                    )
                )
            ),
            'getInstanceByIdTest' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstanceByIdRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstanceByIdResponse',
                    'mapping' => array(
                        'getInstanceByIdResponse' => 'Plugin_BOForms_Model_GetInstanceByIdResponse'
                    )
                )
            ),
            'updateInstance' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_UpdateInstanceRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_UpdateInstanceResponse',
                    'mapping' => array(
                        'updateInstanceResponse' => 'Plugin_BOForms_Model_UpdateInstanceResponse'
                    )
                )
            )
            ,
            'duplicateInstance' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_DuplicateInstanceRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_DuplicateInstanceResponse',
                    'mapping' => array(
                        'duplicateInstanceResponse' => 'Plugin_BOForms_Model_DuplicateInstanceResponse'
                    )
                )
            )
            ,
            'getReferential' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetReferentialRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetReferentialResponse',
                    'mapping' => array(
                        'getReferentialResponse' => 'Plugin_BOForms_Model_GetReferentialResponse'
                    )
                )
            ),
            'getReferentialTest' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetReferentialRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetReferentialResponse',
                    'mapping' => array(
                        'getReferentialResponse' => 'Plugin_BOForms_Model_GetReferentialResponse'
                    )
                )
            ),
            
            'getReporting' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetReportingRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetReportingResponse',
                    'mapping' => array(
                        'getReportingResponse' => 'Plugin_BOForms_Model_GetReportingResponse'
                    )
                )
            ),
            'getLeadsByType' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetLeadsByTypeRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetLeadsByTypeResponse',
                    'mapping' => array(
                        'getLeadsByTypeResponse' => 'Plugin_BOForms_Model_GetLeadsByTypeResponse'
                    )
                )
            ),
            'getParameters' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetParametersRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetParametersResponse',
                    'mapping' => array(
                        'getParametersResponse' => 'Plugin_BOForms_Model_GetParametersResponse'
                    )
                )
            ),
            'updateParameters' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_UpdateParametersRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_UpdateParametersResponse',
                    'mapping' => array(
                        'updateParametersResponse' => 'Plugin_BOForms_Model_UpdateParametersResponse'
                    )
                )
            ),
            'getMasters' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetMastersRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetMastersResponse',
                    'mapping' => array(
                        'getMastersResponse' => 'Plugin_BOForms_Model_GetMastersResponse'
                    )
                )
            ),
            'getInstancesByMaster' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesByMasterRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_GetInstancesByMasterResponse',
                    'mapping' => array(
                        'getInstancesByMasterResponse' => 'Plugin_BOForms_Model_GetInstancesByMasterResponse'
                    )
                )
            ),
            'deleteABTestingInstance' => array(
                'request' => array(
                    'model' => 'Plugin_BOForms_Model_DeleteABTestingInstanceRequest'
                ),
                'response' => array(
                    'model' => 'Plugin_BOForms_Model_DeleteABTestingInstanceResponse',
                    'mapping' => array(
                        'deleteABTestingInstanceResponse' => 'Plugin_BOForms_Model_DeleteABTestingInstanceResponse'
                    )
                )
            )
            
        );
        $this->loggers['default'] = array(
            'writer' => 'file',
            'formater' => 'simple',
            'parameters' => array(
                'file' => \Pelican::$config['VAR_ROOT'].'/logs/BOForms.log'
            )
        );
    }

}
