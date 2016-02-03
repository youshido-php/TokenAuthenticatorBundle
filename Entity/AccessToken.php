<?php

namespace Youshido\TokenAuthenticationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Youshido\DoctrineExtensionBundle\Traits\TimetrackableTrait;

/**
 * AccessToken
 *
 * @ORM\Table()
 * @ORM\Entity
 *
 * @UniqueEntity(fields={"modelId", "value"})
 */
class AccessToken
{

    const STATUS_VALID   = 0;
    const STATUS_EXPIRED = 1;
    const STATUS_DENIED  = 2;

    use TimetrackableTrait;

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
}
