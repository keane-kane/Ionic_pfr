<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\CompteRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=CompteRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"compte:read"}},
 *      denormalizationContext   ={"groups"={"compte:write"}},
 *      attributes={
 *          "force_eager"=false,
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
     * @Groups({
     *      "compte:read", "agence:read",
     *      "trans:read",
     *      "user:read",
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le code est obligatoire")
     * @Groups({
     *      "compte:read",
     *      "agence:read", 
     *      "trans:read",
     *      "users:read", 
     * })
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\GreaterThanOrEqual(
     *     value = 70000
     * )
     * @Assert\NotBlank(message="Le montant est obligatoire")
     * @Groups({
     *      "compte:read", "compte:write",
     *      "agence:read", "agence:write",
     *      "trans:read", "trans:write",
     *      "users:read", "users:write"
     * })
     */
    private $montant;

    /**
     * @ORM\Column(type="datetime")
   * @Groups({
     *      "compte:read", 
     *      "agence:read",
     *      "trans:read", 
     *      "users:read", "users:write"
     * })
     */
    private $createAt;

    /**
     * @ORM\Column(type="boolean")
     *  @Groups({"compte:read", "agence:read"})
     */
    private $archive = 0;
    
    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="compte")
     * @Groups({
     *      "compte:read", "compte:write",
     *      "agence:read","trans:read",
     *      "users:read", "users:write"
     * })
     */
    private $transactions;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comptes")
     * @Groups({
     *      "compte:read", "compte:write",
     *      "agence:read","trans:read",
     *      "users:read"
     * })
     */
    private $users;


    public function __construct()
    {
        $this->transactions = new ArrayCollection();
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
     * @return Collection|Transaction[]
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions[] = $transaction;
            $transaction->setCompte($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transactions->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getCompte() === $this) {
                $transaction->setCompte(null);
            }
        }

        return $this;
    }

    public function getUsers(): ?User
    {
        return $this->users;
    }

    public function setUsers(?User $users): self
    {
        $this->users = $users;

        return $this;
    }


   
}
