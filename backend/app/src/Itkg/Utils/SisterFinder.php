<?php

namespace Itkg\Utils;

/**
 * Class SisterFinder
 * @package Itkg\Utils
 */
class SisterFinder
{
    /**
     * @var array
     */
    protected $reverseSister = [];

    /**
     * @var array
     */
    protected $sisters;

    /**
     * @var ImageCompareUtils
     */
    protected $compareUtils;

    /**
     * SisterFinder constructor.
     *
     * @param ImageCompareUtils $compareUtils
     */
    public function __construct(ImageCompareUtils $compareUtils)
    {
        $this->compareUtils = $compareUtils;
    }

    /**
     * @return array
     */
    public function getSisters()
    {
        return $this->sisters;
    }

    protected function splitHD($signatures)
    {
        $sd = [];
        $hd = [];
        foreach ($signatures as $id => $info) {
            if (($info['width'] > 1280) && ($info['height'] > 720)) {
                $hd[$id] = $info;
            } else {
                $sd[$id] = $info;
            }
        }

        return ['sd' => $sd, 'hd' => $hd];
    }

    protected function hasBetterSister($ecart, $img2)
    {
        return isset($this->reverseSister[$img2]) && $this->reverseSister[$img2] < $ecart;
    }

    /**
     * @param array $signatures
     */
    public function generateSisters($signatures)
    {
        $this->sisters = [];
        $split = $this->splitHD($signatures);
        $max = $this->compareUtils->getMaxDistance();
        foreach ($split['sd'] as $id1 => $infos) {
            $nearest = $max;
            foreach ($split['hd'] as $id2 => $try) {
                $ecart = $this->compareUtils->hammer($infos['signature'], $try['signature']);
                if ($ecart < $nearest && !$this->hasBetterSister($ecart, $id2)) {
                    $this->reverseSister[$id2] = $ecart;
                    $nearest = $ecart;
                    $this->sisters[$id1] = [
                        'id' => $id2,
                        'ecart' => $ecart,
                        'precision' => sprintf('%1.0f%%', (100 - ($ecart / $max) * 100)),
                    ];
                }
            }
        }
    }

    /**
     *  sort result by descendy similarity
     */
    public function sortResult()
    {
        uasort($this->sisters, function ($a, $b) {
            if ($a['ecart'] == $b['ecart']) {
                return 0;
            }

            return ($a['ecart'] < $b['ecart']) ? -1 : 1;
        });
    }
}
