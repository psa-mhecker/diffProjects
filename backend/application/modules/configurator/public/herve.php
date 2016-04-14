<?php
// exemple d'appel du ws: http://media.psa-modules.com/modules/configurator/herve.php

$result = array('result' => array(
                                array('colorId' => 'erarearzer', 'colorValue' => 'C15'),
                                array('colorId' => 'erEZDarzer', 'colorValue' => 'C09')
                             )
                );

echo json_encode($result);



