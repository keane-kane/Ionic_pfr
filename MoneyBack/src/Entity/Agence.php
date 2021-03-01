<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AgenceRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AgenceRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"agence:read"}},
 *      denormalizationContext   ={"groups"={"agence:write"}},
 *      attributes={
 *          "force_eager"=false,
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
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
 *        
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
     * @Groups({"agence:read", "compte:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read", "agence:write","compte:read", "compte:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read", "agence:write", "compte:read", "compte:write"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"agence:read", "agence:write", "compte:read", "compte:write"})
     */
    private $phone;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"agence:read"})
     */
    private $archive = 0;

    /**
     * @ORM\OneToOne(targetEntity=Compte::class, inversedBy="agence", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"agence:read", "agence:write"})
     */
    private $appartient;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="creerAgence")
     * @Groups({"agence:read"})
     */
    private $adminsystem;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="agence", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"agence:read", "agence:write"})
     */
    private $adminagence;

  
    public function __construct()
    {
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

    public function getAppartient(): ?Compte
    {
        return $this->appartient;
    }

    public function setAppartient(?Compte $appartient): self
    {
        $this->appartient = $appartient;

        return $this;
    }

    public function getAdminsystem(): ?User
    {
        return $this->adminsystem;
    }

    public function setAdminsystem(?User $adminsystem): self
    {
        $this->adminsystem = $adminsystem;

        return $this;
    }

    public function getAdminagence(): ?User
    {
        return $this->adminagence;
    }

    public function setAdminagence(?User $adminagence): self
    {
        $this->adminagence = $adminagence;

        return $this;
    }

}
