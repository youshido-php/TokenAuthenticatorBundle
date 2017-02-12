<?php

namespace Youshido\TokenAuthenticationBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenInterface;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenStatus;

/**
 * AccessToken
 *
 * @MongoDB\Document(collection="access_tokens")
 *
 * @UniqueEntity(fields={"modelId", "value"})
 * @MongoDB\HasLifecycleCallbacks()
 */
class AccessToken implements AccessTokenInterface
{

    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     *
     * @MongoDB\Field(type="string", nullable=false)
     */
    private $modelId;

    /**
     * @var int
     *
     * @MongoDB\Field(type="int", nullable=false)
     */
    private $status = AccessTokenStatus::STATUS_VALID;

    /**
     * @var string
     *
     * @MongoDB\Index()
     * @MongoDB\Field(type="string", nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @MongoDB\Field(type="date", nullable=false)
     */
    private $createdAt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set modelId
     *
     * @param string $modelId
     *
     * @return AccessToken
     */
    public function setModelId($modelId)
    {
        $this->modelId = $modelId;

        return $this;
    }

    /**
     * Get modelId
     *
     * @return string
     */
    public function getModelId()
    {
        return $this->modelId;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return AccessToken
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return AccessToken
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     *
     * @return AccessToken
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @MongoDB\PrePersist()
     */
    public function onCreate()
    {
        $this->createdAt = new \DateTime();
    }
}
