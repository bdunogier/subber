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
     * @ORM\Column(name="rating", type="integer", nullable=true)
     */
    protected $rating = 0;

    /**
     * Set file

     *
*@param string $file
     *
*@return WatchListItem
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
*@param string $originalName
     *
*@return WatchListItem
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

    /**
     * @return mixed
     */
    public function getRating()
    {
        return $this->rating;
    }

    /**
     * @param mixed $rating
     */
    public function setRating( $rating )
    {
        $this->rating = $rating;
    }
}
