<?php

namespace PsaNdp\MappingBundle\Repository;

use BadMethodCallException;
use OpenOrchestra\BaseApi\Model\ApiClientInterface;
use OpenOrchestra\BaseApi\Model\TokenInterface;
use OpenOrchestra\BaseApi\Repository\AccessTokenRepositoryInterface;

/**
 * Class AccessTokenRepository
 *
 * Use for orchestra 's dependency
 */
class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    /**
     * @param ApiClientInterface $client
     *
     * @return TokenInterface
     */
    public function findOneByClientWithoutUser(ApiClientInterface $client)
    {
        throw new BadMethodCallException('TODO: Implement findOneByClientWithoutUser() method.');
    }

    /**
     * @param ApiClientInterface $client
     * @param string $refreshToken
     *
     * @return TokenInterface
     */
    public function findOneByClientAndRefreshToken(ApiClientInterface $client, $refreshToken)
    {
        throw new BadMethodCallException('TODO: Implement findOneByClientAndRefreshToken() method.');
    }

    /**
     * @param string $token
     *
     * @return TokenInterface
     */
    public function findOneByCode($token)
    {
        throw new BadMethodCallException('TODO: Implement findOneByCode() method.');
    }

    /**
     * @param array $criteria
     *
     * @return array
     */
    public function findBy(array $criteria)
    {
        throw new BadMethodCallException('TODO: Implement findBy() method.');
    }
}
