<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiResource(
 *      normalizationContext={"groups"={"agence:read"}},
 *      denormalizationContext={"groups"={"agence:write"}},
 *      attributes={
 *          "force_eager"=false,
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_AGENCE')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *     },
 *     collectionOperations={
 *          "get"={
 *                "path"="/agences",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/agences",
 *                "method"="post"
 *              }, 
 *      },
 *     itemOperations={
 *         "GET"={
 *              "path"="/agences/{id}"
 *            },
 *         "PUT"={
 *             "path"="/agences/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/agences/{id}"
 *          },
 *        
 *  }
 * )
 */
class Agence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({
     *      "agence:read", 
     *      "compte:read", 
     *      "users:read",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "agence:read", "agence:write",
     *      "compte:read", "compte:write",
     *      "users:read", "users:write",
     * })
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "agence:read", "agence:write",
     *      "compte:read", "compte:write",
     *      "users:read", "users:write",
     * })
     */
    private $adresse;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "agence:read", "agence:write",
     *      "compte:read", "compte:write",
     *      "users:read", "users:write",
     * })
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
   * @Groups({
     *      "agence:read",
     *      "compte:read", 
     *      "users:read", 
     * })
     */
    private $archive = 0;


    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="agencePartenaire", cascade={"persist", "remove"})
     * @Groups({
     *      "agence:read", "agence:write",
     *      "compte:read", "compte:write",
     *      "users:read", "users:write",
     *      "trans:read","trans:write",
     * })
     */
    private $users;

    /**
     * @ORM\OneToOne(targetEntity=Compte::class, cascade={"persist", "remove"})
     *
     * @Groups({
     *      "agence:read", "agence:write",
     *      "users:read"
     * })
     */
    private $compte;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

 
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

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
            $user->setAgencePartenaire($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getAgencePartenaire() === $this) {
                $user->setAgencePartenaire(null);
            }
        }

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;

        return $this;
    }


  

}
