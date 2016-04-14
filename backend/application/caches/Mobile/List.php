<?php

/**
 */

/**
 * Fichier de Pelican_Cache : Résultat de requête sur site.
 *
 * retour : id, lib
 *
 * @author Raphaël Carles <rcarles@businessdecision.com>
 *
 * @since 20/06/2004
 */
class Mobile_List extends Pelican_Cache
{
    public $duration = UNLIMITED;

    /**
     * Valeur ou objet à mettre en Pelican_Cache.
     */
    public function getValue()
    {
        set_time_limit(3000);
        ini_set("memory_limit", '250M');

        $config ['wurflapi'] = Pelican::$config ['wurflapi'];

        $wurflConfig = WURFL_Configuration_ConfigFactory::create($config ['wurflapi'] ['wurfl_config_file']);
        $wurflManagerFactory = new WURFL_WURFLManagerFactory($wurflConfig);
        $wurflManager = $wurflManagerFactory->create();

        if (! $this->params [0]) {
            $devices = $wurflManager->getAllDevicesID();
            $values = array();
            $total = count($devices);
            foreach ($devices as $id) {
                $device = $wurflManager->getDevice($id);

                // $temp = Pelican_Cache::fetch('Mobile/List',array($id));

                $features = $device->getAllCapabilities();

                $count = (isset($values [$features ['brand_name']]) ? count($values [$features ['brand_name']]) : 0);
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['device_id'] = $device->id;
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['model_name'] = $features ['model_name'];
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['mobile_browser'] = $features ['mobile_browser'];
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['user_agent'] = $device->userAgent;
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['fall_back'] = $device->fallBack;
                $values [$features ['brand_name']] [$features ['model_name'].$count] ['markup'] = $features ['preferred_markup'];

                // $values[$features['brand_name']] ++;
                echo "reste : ".-- $total."<br />";
                flush();
            }
        } else {
            if ($this->params [0] == 'group') {
                $groups = $wurflManager->getListOfGroups();
                foreach ($groups as $cap) {
                    $val = $wurflManager->getCapabilitiesNameForGroup($cap);
                    $values [$cap] = $val;
                }
            } else {
                $aRef = Pelican_Cache::fetch('Mobile/List', array('group' ));

                $device = $wurflManager->getDevice($this->params [0]);

                $capabilities = $device->getAllCapabilities();
                $values ['identity'] ['device_id'] = $device->id;
                $values ['identity'] ['user_agent'] = $device->userAgent;
                $values ['identity'] ['fall_back'] = $device->fallBack;

                foreach ($aRef as $g => $c) {
                    foreach ($c as $k) {
                        if (isset($capabilities [$k])) {
                            $values [$g] [$k] = $capabilities [$k];
                        }
                    }
                }
            }
        }

        $this->value = $values;
    }
}
