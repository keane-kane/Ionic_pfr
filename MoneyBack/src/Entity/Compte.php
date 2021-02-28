<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"compte:read"}},
 *      denormalizationContext   ={"groups"={"compte:write"}},
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
     * @Groups({"agence:read", "compte:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le code est obligatoire")
     * @Groups({"agence:read", "compte:read"})
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\GreaterThanOrEqual(
     *     value = 70000
     * )
     * @Assert\NotBlank(message="Le password est obligatoire")
     * @Groups({"compte:read", "compte:write", "agence:read", "agence:write"})
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"compte:read", "agence:read"})
     */
    private $createAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0;

    /**
     * @ORM\OneToOne(targetEntity=Agence::class, mappedBy="appartient", cascade={"persist", "remove"})
     * @Groups({"agence:read", "agence:write", "compte:read", "compte:write"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $agence;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="caissierdepot")
     */
    private $usercaissier;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="useragence")
     */
    private $usertransaction;

    public function __construct()
    {
  
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

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        // unset the owning side of the relation if necessary
        if ($agence === null && $this->agence !== null) {
            $this->agence->setAppartient(null);
        }

        // set the owning side of the relation if necessary
        if ($agence !== null && $agence->getAppartient() !== $this) {
            $agence->setAppartient($this);
        }

        $this->agence = $agence;

        return $this;
    }

    public function getUsercaissier(): ?User
    {
        return $this->usercaissier;
    }

    public function setUsercaissier(?User $usercaissier): self
    {
        $this->usercaissier = $usercaissier;

        return $this;
    }

    public function getUsertransaction(): ?User
    {
        return $this->usertransaction;
    }

    public function setUsertransaction(?User $usertransaction): self
    {
        $this->usertransaction = $usertransaction;

        return $this;
    }

   
}
