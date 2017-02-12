<?php

namespace Youshido\TokenAuthenticationBundle\Model;


interface AccessTokenInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set modelId
     *
     * @param string $modelId
     *
     * @return $this
     */
    public function setModelId($modelId);

    /**
     * Get modelId
     *
     * @return string
     */
    public function getModelId();

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return $this
     */
    public function setStatus($status);

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus();

    /**
     * Set value
     *
     * @param string $value
     *
     * @return $this
     */
    public function setValue($value);

    /**
     * Get value
     *
     * @return string
     */
    public function getValue();

    /**
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * @param \DateTime $createdAt
     *
     * @return $this
     */
    public function setCreatedAt($createdAt);

}