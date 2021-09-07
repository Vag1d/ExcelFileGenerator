<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\EquatableInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, EquatableInterface
{
    const USER_ROLES = [
        'ROLE_ADMIN' => "Администратор",
        'ROLE_USER' => "Пользователь"
    ];

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;
    private $plainPassword;

    /**
     * @ORM\OneToMany(targetEntity=Database::class, mappedBy="author", orphanRemoval=true)
     */
    private $doc;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $fio;

    public function __construct()
    {
        $this->doc = new ArrayCollection();
    } # поле ввода и проверка пороля getPlainPassword и setPlainPassword
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * @return Collection|Database[]
     */
    public function getDoc(): Collection
    {
        return $this->doc;
    }

    public function addDoc(Database $doc): self
    {
        if (!$this->doc->contains($doc)) {
            $this->doc[] = $doc;
            $doc->setAuthor($this);
        }

        return $this;
    }

    public function removeDoc(Database $doc): self
    {
        if ($this->doc->contains($doc)) {
            $this->doc->removeElement($doc);
            // set the owning side to null (unless already changed)
            if ($doc->getAuthor() === $this) {
                $doc->setAuthor(null);
            }
        }

        return $this;
    }

    public function getFio(): ?string
    {
        return $this->fio;
    }

    public function setFio(?string $fio): self
    {
        $this->fio = $fio;

        return $this;
    }

    public function isEqualTo(UserInterface $user)
    {
        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}
