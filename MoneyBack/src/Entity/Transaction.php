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
 *          "get_client"={
 *              "path"="/transactions/{code}",
 *               "method"="GET",
 *               "controller"=App\Controller\TransactionController::class
 *            },
 *         "put"={
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
     * @Groups({
     *      "trans:read", "user:read",
     *      "users:write", "compte:read"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Montant est obligatoire")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $montant;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $frais;

  
    /**
     * @ORM\Column(type="datetime")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $dateDepot;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $dateRetrait;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le Type est obligatoire")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $type;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $partEtat = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $partTransfert = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $partDepot = 0;

    /**
     * @ORM\Column(type="integer")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $partRetrait = 0;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({
     *      "trans:read",
     *      "users:read",
     *      "compte:read"
     * })
     */
    private $archive = 0;

    /**
     * @ORM\OneToOne(targetEntity=Client::class, inversedBy="transaction", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read",
     *      "client:read", "client:write",
     * })
     */
    private $clientTrans;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="transactions", cascade={"persist", "remove"})
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $userAgenceTransaction;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="transactions")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $compte;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $codeValide = 0;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({
     *      "trans:read", "trans:write",
     *      "users:read", "users:write",
     *      "compte:read"
     * })
     */
    private $annulertransac = 0;

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

    public function getDateDepot(): ?\DateTimeInterface
    {
        return $this->dateDepot;
    }

    public function setDateDepot(\DateTimeInterface $dateDepot): self
    {
        $this->dateDepot = $dateDepot;

        return $this;
    }

    public function getDateRetrait(): ?\DateTimeInterface
    {
        return $this->dateRetrait;
    }

    public function setDateRetrait(\DateTimeInterface $dateRetrait): self
    {
        $this->dateRetrait = $dateRetrait;

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

    public function getClientTrans(): ?Client
    {
        return $this->clientTrans;
    }

    public function setClientTrans(?Client $clientTrans): self
    {
        $this->clientTrans = $clientTrans;

        return $this;
    }

    public function getUserAgenceTransaction(): ?User
    {
        return $this->userAgenceTransaction;
    }

    public function setUserAgenceTransaction(?User $userAgenceTransaction): self
    {
        $this->userAgenceTransaction = $userAgenceTransaction;

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

    public function getCodeValide(): ?bool
    {
        return $this->codeValide;
    }

    public function setCodeValide(bool $codeValide): self
    {
        $this->codeValide = $codeValide;

        return $this;
    }

    public function getAnnulertransac(): ?bool
    {
        return $this->annulertransac;
    }

    public function setAnnulertransac(bool $annulertransac): self
    {
        $this->annulertransac = $annulertransac;

        return $this;
    }

  
}
