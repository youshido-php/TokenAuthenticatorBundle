<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service\Helper;

use Doctrine\ORM\EntityManager;
use Youshido\TokenAuthenticationBundle\Entity\AccessToken;

class AccessTokenHelper
{

    /** @var int */
    private $tokenLifetime;

    /** @var  EntityManager */
    private $em;

    public function __construct(EntityManager $em, $tokenLifetime)
    {
        $this->em            = $em;
        $this->tokenLifetime = $tokenLifetime;
    }

    /**
     * @param AccessToken $token
     *
     * @return bool
     */
    public function checkExpires(AccessToken $token)
    {
        if (time() > ($token->getCreatedAt()->getTimestamp() + $this->tokenLifetime)) {
            return false;
        }

        return true;
    }

    /**
     * @param $accessToken
     *
     * @return null|AccessToken
     */
    public function find($accessToken)
    {
        return $this->em->getRepository('TokenAuthenticationBundle:AccessToken')->findOneBy(['value' => $accessToken]);
    }

    /**
     * @param $modelId  int
     * @param $withSave bool
     *
     * @return AccessToken
     */
    public function generateToken($modelId, $withSave = true)
    {
        $token = new AccessToken();

        $token
            ->setModelId($modelId)
            ->setValue(base64_encode(md5(time() . $modelId)));

        if ($withSave) {
            $this->em->persist($token);
            $this->em->flush();
        }

        return $token;
    }

    public function expireToken(AccessToken $accessToken)
    {
        $this->em->remove($accessToken);
        $this->em->flush();
    }

    /**
     * @param $id
     *
     * @return AccessToken
     */
    public function findTokenByModelId($id)
    {
        return $this->em->getRepository('TokenAuthenticationBundle:AccessToken')->findOneBy(['modelId' => $id]);
    }

    public function transformToArray(AccessToken $token)
    {
        return [
            'accessToken' => $token->getValue(),
            'expiresAt'   => (new \DateTime())->setTimestamp($token->getCreatedAt()->getTimestamp() + $this->tokenLifetime),
        ];
    }

    /**
     * @return int
     */
    public function getTokenLifetime()
    {
        return $this->tokenLifetime;
    }

    /**
     * @param int $tokenLifetime
     */
    public function setTokenLifetime($tokenLifetime)
    {
        $this->tokenLifetime = $tokenLifetime;
    }
}
