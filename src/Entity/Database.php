<?php

namespace App\Entity;

use App\Repository\DatabaseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DatabaseRepository::class)
 * @ORM\Table(name="`database`")
 */
class Database
{
    const TEMPLATES = ['Администрация', 'Сельская школа', 'Городская школа'];
    const PROTOCOLS = ['Протокол', 'Протокол ФП'];
    const PROTOCOL_FP_ID = 1;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fullNameC30;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $shortName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $FIO;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $spot;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $adres;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ystaw;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spotNameFP;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $mainFIOFP;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="doc")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated_at;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numGk;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $protocol;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $template;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $spot_genitive;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fio_genitive;
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFullNameC30(): ?string
    {
        return $this->fullNameC30;
    }

    public function setFullNameC30(string $fullNameC30): self
    {
        $this->fullNameC30 = $fullNameC30;

        return $this;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }

    public function setShortName(string $shortName): self
    {
        $this->shortName = $shortName;

        return $this;
    }

    public function getFIO(): ?string
    {
        return $this->FIO;
    }

    public function setFIO(string $FIO): self
    {
        $this->FIO = $FIO;

        return $this;
    }

    public function getSpot(): ?string
    {
        return $this->spot;
    }

    public function setSpot(string $spot): self
    {
        $this->spot = $spot;

        return $this;
    }

    public function getAdres(): ?string
    {
        return $this->adres;
    }

    public function setAdres(string $adres): self
    {
        $this->adres = $adres;

        return $this;
    }

    public function getYstaw(): ?string
    {
        return $this->ystaw;
    }

    public function setYstaw(string $ystaw): self
    {
        $this->ystaw = $ystaw;

        return $this;
    }

    public function getSpotNameFP(): ?string
    {
        return $this->spotNameFP;
    }

    public function setSpotNameFP(string $spotNameFP): self
    {
        $this->spotNameFP = $spotNameFP;

        return $this;
    }

    public function getMainFIOFP(): ?string
    {
        return $this->mainFIOFP;
    }

    public function setMainFIOFP(string $mainFIOFP): self
    {
        $this->mainFIOFP = $mainFIOFP;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getNumGk(): ?string
    {
        return $this->numGk;
    }

    public function setNumGk(?string $numGk): self
    {
        $this->numGk = $numGk;

        return $this;
    }

    public function getProtocol(): ?int
    {
        return $this->protocol;
    }

    public function setProtocol(?int $protocol): self
    {
        $this->protocol = $protocol;

        return $this;
    }

    public function getTemplate(): ?int
    {
        return $this->template;
    }

    public function setTemplate(?int $template): self
    {
        $this->template = $template;

        return $this;
    }

    public function getSpotGenitive(): ?string
    {
        return $this->spot_genitive;
    }

    public function setSpotGenitive(?string $spot_genitive): self
    {
        $this->spot_genitive = $spot_genitive;

        return $this;
    }

    public function getFioGenitive(): ?string
    {
        return $this->fio_genitive;
    }

    public function setFioGenitive(?string $fio_genitive): self
    {
        $this->fio_genitive = $fio_genitive;

        return $this;
    }
}
