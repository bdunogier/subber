<?php
namespace BD\Subber\WatchList;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="BD\Subber\WatchList\DoctrineWatchList")
 * @ORM\Table(name="tasks")
 */
class WatchListItem
{
    const STATUS_NEW = 0;
    const STATUS_DONE = 1;
    const STATUS_FINISHED = 2;

    public function __construct( array $properties = [] )
    {
        foreach ( $properties as $property => $value )
        {
            if ( !property_exists( $this, $property ) )
                throw new \InvalidArgumentException( "Unknown property $property in class " . __CLASS__ );
            $this->$property = $value;
        }
    }

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
    protected $createdAt = null;

    /**
     * @ORM\Column(name="updated", type="datetime", nullable=true)
     */
    protected $updatedAt = null;

    /**
     * @ORM\Column(name="status", type="integer", nullable=true)
     */
    protected $status = 0;

    /**
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    protected $rating = 0;

    /**
     * @ORM\Column(name="has_subtitle", type="integer", nullable=false, options={"default":0})
     */
    protected $hasSubtitle = 0;

    /**
     * Set file
     * @param string $file
     * @return WatchListItem
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
     * @param string $originalName
     * @return WatchListItem
     */
    public function setOriginalName($originalName)
    {
        $this->originalName = $originalName;
        return $this;
    }

    /**
     * Get originalName
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
     * @return WatchListItem
     */
    public function setStatus( $status )
    {
        $this->status = $status;
        return $this;
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
     * @return WatchListItem
     */
    public function setUpdatedAt( $updatedAt )
    {
        $this->updatedAt = $updatedAt;
        return $this;
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
     * @return WatchListItem
     */
    public function setCreatedAt( $createdAt )
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     * @return WatchListItem
     */
    public function setRating( $rating )
    {
        $this->rating = $rating;
        return $this;
    }

    public function setHasSubtitle( $has = true )
    {
        $this->hasSubtitle = (int)$has;
        return $this;
    }

    public function hasSubtitle()
    {
        return (bool)$this->hasSubtitle;
    }
}
