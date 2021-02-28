<?php

namespace App\Entity;
use App\Entity\Profils;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\SerializedName;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      
 *      normalizationContext   ={"groups"={"users:read"}},
 *      denormalizationContext   ={"groups"={"users:write"}},
 *      attributes={
 *          "pagination_items_per_page"=30,
 *          "security"="is_granted('ROLE_ADMIN_SYS')",
 *          "security_message"="Acces refusé vous n'avez pas l'autorisation"
 *      },
 *      collectionOperations={
 *          "get"={
 *                "path"="/users",
 *                "method"="get"
 *              },  
 *           "post"={
 *                "path"="/users",
 *                "method"="post",
 *                "security_post_denormalize"="is_granted('EDIT', object)",
 *              }, 
 *        
 *      },
 *      itemOperations={
 *         "GET"={
 *              "path"="/users/{id}"
 *            },
 *         "PUT"={
 *             "path"="/users/{id}"
 *          },
 *        "DELETE"={
 *             "path"="/users/{id}"
 *          },
 *        
 *  }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"trans:read", "users:read"})
     * @Groups({"agence:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\Email
     * @Assert\NotBlank(message="Le mail est obligatoire")
     * @Groups({"trans:read", "users:write"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $email;

  
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire")
     * @Groups({"trans:read", "users:read", "users:write"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $password;

  
    /**
     * @ORM\ManyToOne(targetEntity=profils::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"profil:read","profil:write","trans:read", "users:write"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $profil;

    /**
    * @SerializedName("password")
    */
    private $plainPassword;

    /**
     * @ORM\Column(type="boolean")
     */
    private $archive = 0; 

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le prenom est obligatoire")
     * @Groups({"trans:read"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Groups({"trans:read"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero est obligatoire")
     * @Groups({"trans:read"})
     * @Groups({"agence:read", "agence:write"})
     */
    private $phone;

    /**
     * @ORM\OneToMany(targetEntity=Agence::class, mappedBy="adminsystem")
     */
    private $creerAgence;

    /**
     * @ORM\OneToMany(targetEntity=Compte::class, mappedBy="usercaissier")
     */
    private $caissierdepot;

    /**
     * @ORM\OneToMany(targetEntity=Compte::class, mappedBy="usertransaction")
     */
    private $useragence;

    /**
     * @ORM\OneToMany(targetEntity=Transaction::class, mappedBy="usertransaction")
     */
    private $transaction;

    /**
     * @ORM\OneToMany(targetEntity=Agence::class, mappedBy="adminagence", orphanRemoval=true)
     */
    private $agence;

    public function __construct()
    {
        $this->creerAgence = new ArrayCollection();
        $this->caissierdepot = new ArrayCollection();
        $this->useragence = new ArrayCollection();
        $this->transaction = new ArrayCollection();
        $this->agence = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getProfil(): ?profils
    {
        return $this->profil;
    }

    public function setProfil(?profils $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }
    
    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        
        return $this;
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
    
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }
    
    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        $this->plainPassword = null;
    }

    /**
     * @return Collection|Agence[]
     */
    public function getCreerAgence(): Collection
    {
        return $this->creerAgence;
    }

    public function addCreerAgence(Agence $creerAgence): self
    {
        if (!$this->creerAgence->contains($creerAgence)) {
            $this->creerAgence[] = $creerAgence;
            $creerAgence->setAdminsystem($this);
        }

        return $this;
    }

    public function removeCreerAgence(Agence $creerAgence): self
    {
        if ($this->creerAgence->removeElement($creerAgence)) {
            // set the owning side to null (unless already changed)
            if ($creerAgence->getAdminsystem() === $this) {
                $creerAgence->setAdminsystem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getCaissierdepot(): Collection
    {
        return $this->caissierdepot;
    }

    public function addCaissierdepot(Compte $caissierdepot): self
    {
        if (!$this->caissierdepot->contains($caissierdepot)) {
            $this->caissierdepot[] = $caissierdepot;
            $caissierdepot->setUsercaissier($this);
        }

        return $this;
    }

    public function removeCaissierdepot(Compte $caissierdepot): self
    {
        if ($this->caissierdepot->removeElement($caissierdepot)) {
            // set the owning side to null (unless already changed)
            if ($caissierdepot->getUsercaissier() === $this) {
                $caissierdepot->setUsercaissier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Compte[]
     */
    public function getUseragence(): Collection
    {
        return $this->useragence;
    }

    public function addUseragence(Compte $useragence): self
    {
        if (!$this->useragence->contains($useragence)) {
            $this->useragence[] = $useragence;
            $useragence->setUsertransaction($this);
        }

        return $this;
    }

    public function removeUseragence(Compte $useragence): self
    {
        if ($this->useragence->removeElement($useragence)) {
            // set the owning side to null (unless already changed)
            if ($useragence->getUsertransaction() === $this) {
                $useragence->setUsertransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Transaction[]
     */
    public function getTransaction(): Collection
    {
        return $this->transaction;
    }

    public function addTransaction(Transaction $transaction): self
    {
        if (!$this->transaction->contains($transaction)) {
            $this->transaction[] = $transaction;
            $transaction->setUsertransaction($this);
        }

        return $this;
    }

    public function removeTransaction(Transaction $transaction): self
    {
        if ($this->transaction->removeElement($transaction)) {
            // set the owning side to null (unless already changed)
            if ($transaction->getUsertransaction() === $this) {
                $transaction->setUsertransaction(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Agence[]
     */
    public function getAgence(): Collection
    {
        return $this->agence;
    }

    public function addAgence(Agence $agence): self
    {
        if (!$this->agence->contains($agence)) {
            $this->agence[] = $agence;
            $agence->setAdminagence($this);
        }

        return $this;
    }

    public function removeAgence(Agence $agence): self
    {
        if ($this->agence->removeElement($agence)) {
            // set the owning side to null (unless already changed)
            if ($agence->getAdminagence() === $this) {
                $agence->setAdminagence(null);
            }
        }

        return $this;
    }
 
}
