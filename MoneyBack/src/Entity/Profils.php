<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProfilsRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ProfilsRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"profil:read"}},
 *      denormalizationContext   ={"groups"={"profil:write"}},
 *      attributes={
 *          "force_eager"=false,
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *     },
 *     collectionOperations={
 *          "get"={
 *                "path"="/profils",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/profils",
 *                "method"="post"
 *              }, 
 *        
 *      },
 *     itemOperations={
 *         "GET"={
 *              "path"="/profils/{id}"
 *            },
 *         "PUT"={
 *             "path"="/profils/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/profils/{id}"
 *          },
 *        
 *  }
 * )
 */
class Profils
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"profil:read","profil:write"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"profil:read","profil:write"})
     */
    private $libelle;
    
    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil", orphanRemoval=true)
     * @ApiSubresource()
     * @Groups({"profil:read","profil:write"})
     */
    private $users;
    
    /**
     * @ORM\Column(type="boolean")
     * @Groups({"profil:read","profil:write"})
     */
    private $archive = 0; 
    
    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }
    
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }
    
    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;
        
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
            $user->setProfil($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getProfil() === $this) {
                $user->setProfil(null);
            }
        }

        return $this;
    }

}
