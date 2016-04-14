<?php


namespace Itkg\Migration\Event;

/**
 * Interface CallableEventsInterface
 */
interface CallableEventsInterface
{

    /**
     * @return array
     */
    public function getCallableEvents();

    /**
     * @param string $callableType
     * @param mixed $callableObject
     * @param string $callableFunction
     *
     * @return CallableEventsInterface
     */
    public function addCallableEvents($callableType, $callableObject, $callableFunction);

    /**
     * @param $callableType
     *
     * @return array
     */
    public function launchCallableEvent($callableType);

}
