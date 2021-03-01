<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 * normalizationContext   ={"groups"={"client:read"}},
 *      attributes={
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *     },
 *     collectionOperations={
 *          "get"={
 *                "path"="/clients",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/clients",
 *                "method"="post"
 *              }, 
 *        
 *      },
 *     itemOperations={
 *         "GET"={
 *              "path"="/clients/{id}"
 *            },
 *         "PUT"={
 *             "path"="/clients/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/clients/{id}"
 *          },
 *        
 *  }
 * )
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"trans:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"trans:read"})
     */
    private $archive = 0;

 
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du client est obligatoire")
     * @Groups({"trans:read","trans:write"})
     */
    private $nomClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du beneficiaire est obligatoire")
     * @Groups({"trans:read","trans:write"})
     */
    private $nomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le cni du client est obligatoire")
     * @Groups({"trans:read","trans:write"})
     */
    private $cniClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read","trans:write"})
     */
    private $cniBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero du client est obligatoire")
     * @Groups({"trans:read","trans:write"})
     */
    private $phoneClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero du beneficiaire est obligatoire")
     * @Groups({"trans:read","trans:write"})
     */
    private $phoneBeneficiaire;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientdepot")
     */
    private $depot;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="clientretrait")
     */
    private $retrait;

    public function __construct()
    {
        $this->depot = new ArrayCollection();
        $this->retrait = new ArrayCollection();
    } 

    public function getId(): ?int
    {
        return $this->id;
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
    
    public function getNomClient(): ?string
    {
        return $this->nomClient;
    }

    public function setNomClient(string $nomClient): self
    {
        $this->nomClient = $nomClient;

        return $this;
    }

    public function getNomBeneficiaire(): ?string
    {
        return $this->nomBeneficiaire;
    }

    public function setNomBeneficiaire(string $nomBeneficiaire): self
    {
        $this->nomBeneficiaire = $nomBeneficiaire;

        return $this;
    }

    public function getCniClient(): ?string
    {
        return $this->cniClient;
    }

    public function setCniClient(string $cniClient): self
    {
        $this->cniClient = $cniClient;

        return $this;
    }

    public function getCniBeneficiaire(): ?string
    {
        return $this->cniBeneficiaire;
    }

    public function setCniBeneficiaire(string $cniBeneficiaire): self
    {
        $this->cniBeneficiaire = $cniBeneficiaire;

        return $this;
    }

    public function getPhoneClient(): ?string
    {
        return $this->phoneClient;
    }

    public function setPhoneClient(string $phoneClient): self
    {
        $this->phoneClient = $phoneClient;

        return $this;
    }

    public function getPhoneBeneficiaire(): ?string
    {
        return $this->phoneBeneficiaire;
    }

    public function setPhoneBeneficiaire(string $phoneBeneficiaire): self
    {
        $this->phoneBeneficiaire = $phoneBeneficiaire;

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getDepot(): Collection
    {
        return $this->depot;
    }

    public function addDepot(Transaction $depot): self
    {
        if (!$this->depot->contains($depot)) {
            $this->depot[] = $depot;
            $depot->setClientdepot($this);
        }

        return $this;
    }

    public function removeDepot(Transaction $depot): self
    {
        if ($this->depot->removeElement($depot)) {
            // set the owning side to null (unless already changed)
            if ($depot->getClientdepot() === $this) {
                $depot->setClientdepot(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getRetrait(): Collection
    {
        return $this->retrait;
    }

    public function addRetrait(Transaction $retrait): self
    {
        if (!$this->retrait->contains($retrait)) {
            $this->retrait[] = $retrait;
            $retrait->setClientretrait($this);
        }

        return $this;
    }

    public function removeRetrait(Transaction $retrait): self
    {
        if ($this->retrait->removeElement($retrait)) {
            // set the owning side to null (unless already changed)
            if ($retrait->getClientretrait() === $this) {
                $retrait->setClientretrait(null);
            }
        }

        return $this;
    }
}
