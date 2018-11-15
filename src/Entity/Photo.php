<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * @ORM\Entity(repositoryClass="App\Repository\PhotoRepository")
 * @Vich\Uploadable()
 */
class Photo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Flayer", inversedBy="photos")
     * @ORM\JoinColumn(nullable=false)
     */
    private $flayer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $path;

	/**
	 * @var File|null
	 * @Vich\UploadableField(mapping="flayers",fileNameProperty="path")
	 */
    private $imageFile;

    /**
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime")
     */
    private $updated_at;

    /**
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFlayer(): ?Flayer
    {
        return $this->flayer;
    }

    public function setFlayer(?Flayer $flayer): self
    {
        $this->flayer = $flayer;

        return $this;
    }

    public function getPath(): ?string
    {
        return $this->path;
    }

    public function setPath(string $path): self
    {
        $this->path = $path;

        return $this;
    }

	/**
	 * @return mixed
	 */
	public function getImageFile()
	{
        return $this->imageFile;
    }

	/**
	 * @param $image
	 *
	 */
	public function setImageFile( $image )
	{
		$this->imageFile = $image;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    /*public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }*/

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    /*public function setUpdatedAt(\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }*/
}
