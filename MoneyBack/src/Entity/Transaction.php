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
     * @ORM\Column(type="datetime")
     * @Groups({"trans:read","trans:write"})
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
     * @Groups({"trans:read"})
     */
    private $partEtat;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read"})
     */
    private $partTransfert;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read"})
     */
    private $partDepot;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"trans:read"})
     */
    private $partRetrait;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"trans:read", "trans:write"})
     */
    private $archive = 0;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="transact")
     * @ApiSubresource()
     * @Groups({"trans:read", "trans:write"})
     * @Assert\NotBlank(message="Le user agence est obligatoire")
     */
    private $users;
    
    /**
     * @ORM\OneToMany(targetEntity=Client::class, mappedBy="faire")
     * @ApiSubresource()
     * @Groups({"trans:read", "trans:write"})
     * @Assert\NotBlank(message="Le Client est obligatoire")
     */
    private $clients;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->clients = new ArrayCollection();
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
            $user->setTransact($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getTransact() === $this) {
                $user->setTransact(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Client[]
     */
    public function getClients(): Collection
    {
        return $this->clients;
    }

    public function addClient(Client $client): self
    {
        if (!$this->clients->contains($client)) {
            $this->clients[] = $client;
            $client->setFaire($this);
        }

        return $this;
    }

    public function removeClient(Client $client): self
    {
        if ($this->clients->removeElement($client)) {
            // set the owning side to null (unless already changed)
            if ($client->getFaire() === $this) {
                $client->setFaire(null);
            }
        }

        return $this;
    }
}
