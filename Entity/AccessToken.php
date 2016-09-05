<?php

namespace Youshido\TokenAuthenticationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * AccessToken
 *
 * @ORM\Table(indexes={
 *    @ORM\Index(name="search_inx", columns={"value"})
 * })
 * @ORM\Entity
 *
 * @UniqueEntity(fields={"modelId", "value"})
 * @ORM\HasLifecycleCallbacks
 */
class AccessToken
{

    const STATUS_VALID   = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_DENIED  = 2;

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $modelId;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    private $status = self::STATUS_VALID;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private $value;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
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
     * @param integer $modelId
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
     * @return integer
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
     * @ORM\PrePersist()
     */
    public function onCreate()
    {
        $this->createdAt = new \DateTime();
    }
}
