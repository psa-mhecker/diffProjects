<?php

namespace Citroen\Perso\Score;

class IndicateurManager {

    public function __construct() {
        $this->_client = new \MongoClient(
                \Pelican::$config['MONGODB_URI'], \Pelican::$config['MONGODB_PARAMS']
        );
        $this->indicateurCollection = $this->_client->selectCollection(
                \Pelican::$config['MONGODB_PARAMS']['db'], \Pelican::$config['MONGODB_CITROEN']['PERSO_INDICATEUR_COLLECTION_NAME']
        );
    }

    private function processFilters($aFilters) {
        if (isset($aFilters['user_id']) && null != $aFilters['user_id']) {
            unset($aFilters['session_id']);
        }
        return $aFilters;
    }

    public function pruneData($aFilters) {
        $aFilters = $this->processFilters($aFilters);
        $this->indicateurCollection->remove($aFilters);
    }

    public function getAllUsers() {
        return $this->indicateurCollection->distinct(
                'user_id', array(
                    'user_id' => array(
                        '$ne' => null
                    )
                )
        );
    }

    public function getAllByUser($sSessionId = null, $iUserId = null) {
        if ($iUserId != null) {
            $iUserId = (int)$iUserId;
        }
        $aFilters = array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
        );
        if (isset($iUserId) && null != $iUserId) {
            unset($aFilters['session_id']);
        }

        return $this->indicateurCollection->find($aFilters);
    }

    public function saveIndicateur($iUserId = null, $sSessionId = null, $aData) {
        if ($iUserId != null) {
            $iUserId = (int)$iUserId;
        }
        $aFilters = array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
        );

        if (isset($iUserId) && null != $iUserId) {
            unset($aFilters['session_id']);
        }

        $aData['time'] = time();


        $aUpdateOperators = array(
            '$set' => $aData
        );

        $aResult = $this->indicateurCollection->findAndModify(
                $aFilters, $aUpdateOperators, null, array(
            'upsert' => true,
            'new' => true
                )
        );
        return $aResult;
    }

}