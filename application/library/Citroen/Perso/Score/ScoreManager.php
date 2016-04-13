<?php

namespace Citroen\Perso\Score;

class ScoreManager {

    public function __construct() {
        $this->_client = new \MongoClient(
                \Pelican::$config['MONGODB_URI'], \Pelican::$config['MONGODB_PARAMS']
        );
        $this->persoScoreCollection = $this->_client->selectCollection(
                \Pelican::$config['MONGODB_PARAMS']['db'], \Pelican::$config['MONGODB_CITROEN']['PERSO_SCORE_COLLECTION_NAME']
        );
    }

    private function processFilters($aFilters) {
        if (isset($aFilters['user_id']) && null != $aFilters['user_id']) {
            unset($aFilters['session_id']);
        }
        return $aFilters;
    }

    public function getUserProductScore($sSessionId, $iProductId, $iUserId = null, $aProjection = array()) {
        if ($iUserId != null) {
            $iUserId = (int) $iUserId;
        }
        $aFilters = $this->processFilters(array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
            'product' => $iProductId
                )
        );


        if (is_array($aProjection) && count($aProjection)) {

            $aResults = $this->persoScoreCollection->findOne($aFilters, $aProjection);
        } else {
            $aResults = $this->persoScoreCollection->findOne($aFilters);
        }

        return $aResults;
    }

    public function getProductWithMaxScoreByUser($sSessionId, $iUserId = null) {
        if ($iUserId != null) {
            $iUserId = (int) $iUserId;
        }
        $aFilters = $this->processFilters(array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
                )
        );
        $aResult = $this->persoScoreCollection->find($aFilters)->sort(array('score' => -1))->limit(1);
        return $aResult->getNext();
    }

    public function getMostRecentProductByUser($sSessionId, $iUserId = null) {
        if ($iUserId != null) {
            $iUserId = (int) $iUserId;
        }
        $aFilters = $this->processFilters(array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
                )
        );
        if (isset($iUserId) && null != $iUserId) {
            unset($aFilters['session_id']);
        }
        $aResult = $this->persoScoreCollection->find($aFilters)->sort(array('time' => -1))->limit(1);
        return $aResult->getNext();
    }

    public function getAllProductsByUser($sSessionId, $iUserId = null, $bForceUserToNull = false) {
        if ($iUserId != null) {
            $iUserId = (int) $iUserId;
        }
        if (!$bForceUserToNull) {
            $aFilters = $this->processFilters(array(
                'user_id' => $iUserId,
                'session_id' => $sSessionId,
                    )
            );
        } else {
            $aFilters = array(
                'user_id' => $iUserId,
                'session_id' => $sSessionId,);
        }

        return $this->persoScoreCollection->find($aFilters);
    }

    public function pruneData($aFilters) {
        $aFilters = $this->processFilters($aFilters);
        $this->persoScoreCollection->remove($aFilters);
    }

    public function saveProductScore($iUserId = null, $iProductId, $sSessionId, $fScore, $time, $siteId) {
        if ($iUserId != null) {
            $iUserId = (int) $iUserId;
        }
        $aFilters = $this->processFilters(array(
            'user_id' => $iUserId,
            'session_id' => $sSessionId,
            'product' => $iProductId,
            'site_id' => $siteId
                )
        );
        /* fetch saved produc score if any */
// public function getUserProductScore($sSessionId, $iProductId, $iUserId = null, $aProjection = array())

        $aStoredPageProduct = $this->getUserProductScore(
                $aFilters['session_id'], $aFilters['product'], $aFilters['user_id']
        );

        if ($aStoredPageProduct) {
            $fNewScore = max(
                    $aStoredPageProduct['score'], $fScore
            );
        } else {
            $fNewScore = $fScore;
        }

        $aUpdateOperators = array(
            '$set' => array(
                'time' => $time,
                'score' => (float) $fNewScore,
                'session_id' => $sSessionId,
                'user_id' => $iUserId
            )
        );

        $aResult = $this->persoScoreCollection->findAndModify(
                $aFilters, $aUpdateOperators, null, array(
            'upsert' => true,
            'new' => true
                )
        );
        return $aResult;
    }

}
