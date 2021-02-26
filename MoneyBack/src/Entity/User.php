<?php

namespace App\Entity;
use App\Entity\Profils;
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
 *      denormalizationContext   ={"groups"={"users:read"}},
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
     * @Groups({"trans:read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Assert\NotBlank(message="Le mail est obligatoire")
     * @Groups({"trans:read"})
     */
    private $email;

  
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Assert\NotBlank(message="Le password est obligatoire")
     */
    private $password;

  
    /**
     * @ORM\ManyToOne(targetEntity=profils::class, inversedBy="users", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"profil:read","profil:write","trans:read"})
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
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom est obligatoire")
     * @Groups({"trans:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero est obligatoire")
     * @Groups({"trans:read"})
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=Compte::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $caissier;

    /**
     * @ORM\ManyToOne(targetEntity=Transaction::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     */
    private $transact;

    /**
     * @ORM\ManyToOne(targetEntity=Agence::class, inversedBy="users")
     * @ORM\JoinColumn(nullable=true)
     * @Groups({"trans:read"})
     */
    private $agence;

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

    public function getCaissier(): ?Compte
    {
        return $this->caissier;
    }

    public function setCaissier(?Compte $caissier): self
    {
        $this->caissier = $caissier;

        return $this;
    }

    public function getTransact(): ?Transaction
    {
        return $this->transact;
    }

    public function setTransact(?Transaction $transact): self
    {
        $this->transact = $transact;

        return $this;
    }

    public function getAgence(): ?Agence
    {
        return $this->agence;
    }

    public function setAgence(?Agence $agence): self
    {
        $this->agence = $agence;

        return $this;
    }
    
}
