<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 * normalizationContext   ={"groups"={"compte:read"}},
 *      attributes={
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *     },
 *     collectionOperations={
 *          "get"={
 *                "path"="/comptes",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/comptes",
 *                "method"="post"
 *              }, 
 *        
 *      },
 *     itemOperations={
 *         "GET"={
 *              "path"="/comptes/{id}"
 *            },
 *         "PUT"={
 *             "path"="/comptes/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/comptes/{id}"
 *          },
 *        
 *  }
 * )
 */
class Compte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="caissier", orphanRemoval=true)
     */
    private $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getMontant(): ?string
    {
        return $this->montant;
    }

    public function setMontant(string $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getCreateAt(): ?\DateTimeInterface
    {
        return $this->createAt;
    }

    public function setCreateAt(\DateTimeInterface $createAt): self
    {
        $this->createAt = $createAt;

        return $this;
    }
    
    public function getArchive(): ?bool
    {
        return $this->archive;
    }
    
    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;
        
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setCaissier($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getCaissier() === $this) {
                $user->setCaissier(null);
            }
        }

        return $this;
    }
}
