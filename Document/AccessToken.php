<?php

namespace Youshido\TokenAuthenticationBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenInterface;
use Youshido\TokenAuthenticationBundle\Model\AccessTokenStatus;

/**
 * AccessToken
 *
 * @ODM\Document(collection="access_tokens")
 *
 * @UniqueEntity(fields={"modelId", "value"})
 * @ODM\HasLifecycleCallbacks()
 */
class AccessToken implements AccessTokenInterface
{

    /**
     * @ODM\Id
     */
    private $id;

    /**
     * @var string
     *
     * @ODM\Field(type="object_id")
     */
    private $modelId;

    /**
     * @var int
     *
     * @ODM\Field(type="int")
     */
    private $status = AccessTokenStatus::STATUS_VALID;

    /**
     * @var string
     *
     * @ODM\Index()
     * @ODM\Field(type="string")
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ODM\Field(type="date")
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
     * @ODM\PrePersist()
     */
    public function onCreate()
    {
        $this->createdAt = new \DateTime();
    }
}
