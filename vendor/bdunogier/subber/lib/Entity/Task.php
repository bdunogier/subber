<?php
/**
 * This file is part of the eZ Publish Kernel package
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace BD\Subber\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="tasks")
 */
class Task
{
    /**
     * Local path to the file that needs subbing
     * @ORM\Column(type="string", length=255)
     * @ORM\Id
     */
    protected $file;

    /**
     * @ORM\Column(name="original_name", type="string", length=100)
     */
    protected $originalName;

    /**
     * @ORM\Column(name="created", type="datetime", nullable=true)
     */
    protected $createdAt = 0;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updatedAt = 0;

    /**
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status = 0;

    /**
     * Set file
     *
     * @param string $file
     * @return Task
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string 
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set originalName
     *
     * @param string $originalName
     * @return Task
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * Get originalName
     *
     * @return string 
     */
    public function getOriginalName()
    {
        return $this->originalName;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus( $status )
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt( $updatedAt )
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;
    }
}
