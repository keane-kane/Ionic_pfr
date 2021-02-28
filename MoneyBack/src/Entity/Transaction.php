<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\TransactionRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=TransactionRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"trans:read"}},
 *      denormalizationContext   ={"groups"={"trans:write"}},
 *      attributes={
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *     },
 *     collectionOperations={
 *          "get"={
 *                "path"="/transactions",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/transactions",
 *                "method"="post"
 *              }, 
 *        
 *      },
 *     itemOperations={
 *         "GET"={
 *              "path"="/transactions/{id}"
 *            },
 *         "PUT"={
 *             "path"="/transactions/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/transactions/{id}"
 *          },
 *        
 *  }
 * )
 */
class Transaction
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"trans:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read"})
     * @Assert\NotBlank(message="Le Code est obligatoire")
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read","trans:write"})
     * @Assert\NotBlank(message="Le Montant est obligatoire")
     */
    private $montant;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read","trans:write"})
     */
    private $frais;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank(message="La Date est obligatoire")
     */
    private $createAt;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read","trans:write"})
     * @Assert\NotBlank(message="Le Type est obligatoire")
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La part de l'etat est obligatoire")
     * @Groups({"trans:read"})
     */
    private $partEtat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La part de l'agence est obligatoire")
     * @Groups({"trans:read"})
     */
    private $partTransfert;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La part de depôt est obligatoire")
     * @Groups({"trans:read"})
     */
    private $partDepot;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="La part de retrait est obligatoire")
     * @Groups({"trans:read"})
     */
    private $partRetrait;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"trans:read"})
     */
    private $archive = 0;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transaction")
     * @Groups({"trans:read"})
     */
    private $usertransaction;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="depot")
     * @Groups({"trans:read","trans:write"})
     */
    private $clientdepot;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="retrait")
     * @Groups({"trans:read", "trans:write"})
     */
    private $clientretrait;


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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPartEtat(): ?string
    {
        return $this->partEtat;
    }

    public function setPartEtat(string $partEtat): self
    {
        $this->partEtat = $partEtat;

        return $this;
    }

    public function getPartTransfert(): ?string
    {
        return $this->partTransfert;
    }

    public function setPartTransfert(string $partTransfert): self
    {
        $this->partTransfert = $partTransfert;

        return $this;
    }

    public function getPartDepot(): ?string
    {
        return $this->partDepot;
    }

    public function setPartDepot(string $partDepot): self
    {
        $this->partDepot = $partDepot;

        return $this;
    }

    public function getPartRetrait(): ?string
    {
        return $this->partRetrait;
    }

    public function setPartRetrait(string $partRetrait): self
    {
        $this->partRetrait = $partRetrait;

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
     * Get the value of frais
     */
    public function getFrais()
    {
        return $this->frais;
    }

    /**
     * Set the value of frais
     *
     * @return  self
     */
    public function setFrais($frais)
    {
        $this->frais = $frais;

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

    public function getClientdepot(): ?Client
    {
        return $this->clientdepot;
    }

    public function setClientdepot(?Client $clientdepot): self
    {
        $this->clientdepot = $clientdepot;

        return $this;
    }

    public function getClientretrait(): ?Client
    {
        return $this->clientretrait;
    }

    public function setClientretrait(?Client $clientretrait): self
    {
        $this->clientretrait = $clientretrait;

        return $this;
    }
}
