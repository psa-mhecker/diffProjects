<?php

namespace PsaNdp\MappingBundle\Datalayer;

use PsaNdp\MappingBundle\Repository\PsaPageDatalayerRepository;

class DatalayerProcessor
{
    /**
     * @var PsaPageDatalayerRepository
     */
    protected $pageDatalayerRepository;

    /**
     * @var array
     */
    protected $datalayerVariables;

    /**
     * @var null|Context
     */
    private $context;

    protected $contextReplacementMap = array(
        'pageTitle' => 'getName',
        'template' => 'getLayoutName',

    );

    /**
     * @param PsaPageDatalayerRepository $pageDatalayerRepository
     */
    public function __construct(PsaPageDatalayerRepository $pageDatalayerRepository)
    {
        $this->pageDatalayerRepository = $pageDatalayerRepository;
        $this->datalayerVariables = array();
        $this->context = null;
    }

    /**
     *
     */
    public function process(&$node)
    {
        $datalayer = $this->pageDatalayerRepository->findOneBy(
            array(
                'pageId' => $this->context->getNode()->getId(),
                'langueId' => $this->context->getNode()->getLangueId(),
            )
        );

        if ($datalayer) {
            $this->datalayerVariables = $datalayer->getContentAsArray();
        }

        array_walk($this->datalayerVariables, array($this, 'contextualize'));

        $node->setDatalayer($this->datalayerVariables);
    }

    /**
     * Get Context context.
     */
    public function getContext()
    {
        return $this->context;
    }

    /**
     * @param Context $context
     *
     * @return DatalayerProcessor
     */
    public function setContext(Context $context)
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @param $value
     * @param $key
     */
    private function contextualize($value, $key)
    {
        $subValueParts = $this->extractSubParts($value);

        if (!empty($subValueParts[1])) {
            for ($counter = 0; $counter < count($subValueParts[1]); ++$counter) {
                if ($this->seekInLocalDefinition($subValueParts[1][$counter]) !== false) {
                    $value = str_replace(
                        $subValueParts[0][$counter],
                        $this->datalayerVariables[trim($subValueParts[1][$counter], '%')],
                        $value
                    );
                } else {
                    $value = str_replace(
                        $subValueParts[0][$counter],
                        $this->context->{$subValueParts[1][$counter]}(),
                        $value
                    );
                }
            }
        }

        $this->datalayerVariables[$key] = strtolower($value);
    }

    /**
     * @param $value
     *
     * @return bool|int
     */
    private function seekInLocalDefinition($value)
    {
        return strpos($value, '%');
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    private function extractSubParts($value)
    {
        $regex = '/{(.*?)}/';
        preg_match_all($regex, $value, $matches);

        return $matches;
    }
}
