<?php
/**
 * Date: 22.12.15
 *
 * @author Portey Vasil <portey@gmail.com>
 */

namespace Youshido\TokenAuthenticationBundle\Service\Helper;

use Doctrine\Common\Persistence\ObjectManager;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenInterface;
use Youshido\TokenAuthenticationBundle\Service\UniversalObjectManager;

class AccessTokenHelper
{
    /** @var int */
    private $tokenLifetime;

    /** @var UniversalObjectManager */
    private $om;

    /** @var string */
    private $platform;

    /** @var int */
    private $odmVersion;

    public function __construct($om, $tokenLifetime, $platform, $odmVersion)
    {
        $this->om            = $om;
        $this->tokenLifetime = $tokenLifetime;
        $this->platform      = $platform;
        $this->odmVersion    = $odmVersion;
    }

    /**
     * @param AccessTokenInterface $token
     *
     * @return bool
     */
    public function checkExpires(AccessTokenInterface $token)
    {
        if (time() > ($token->getCreatedAt()->getTimestamp() + $this->tokenLifetime)) {
            return false;
        }

        return true;
    }

    /**
     * @param $accessToken
     *
     * @return null|AccessTokenInterface
     */
    public function find($accessToken)
    {
        return $this->om->getRepository('TokenAuthenticationBundle:AccessToken')->findOneBy(['value' => $accessToken]);
    }

    /**
     * @param $modelId  string
     * @param $withSave bool
     *
     * @return AccessTokenInterface
     */
    public function generateToken($modelId, $withSave = true)
    {
        $token = $this->om->createNewTokenInstance();

        $tokenValue = md5(time() . $modelId) . ':' . json_encode(['id' => $modelId]);
        $tokenValue = str_replace('=', '', base64_encode($tokenValue));

        $token
            ->setModelId($modelId)
            ->setValue($tokenValue);

        if ($withSave) {
            $this->om->persist($token);
            $this->om->flush();
        }

        return $token;
    }

    public function expireToken(AccessTokenInterface $accessToken)
    {
        $this->om->remove($accessToken);
        $this->om->flush();
    }

    /**
     * @param $id
     *
     * @return AccessTokenInterface
     */
    public function findTokenByModelId($id)
    {
        if ($this->platform === UniversalObjectManager::PLATFORM_ODM) {
            switch ($this->odmVersion) {
                case 1:
                    $id = new \MongoId($id);
                    break;
                case 2:
                    $id = new \MongoDB\BSON\ObjectId($id);
                    break;
                default:
                    throw new \UnexpectedValueException('Unknown ODM version');
            }
        }

        return $this->om->getRepository('TokenAuthenticationBundle:AccessToken')->findOneBy(['modelId' => $id]);
    }

    public function transformToArray(AccessTokenInterface $token)
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
