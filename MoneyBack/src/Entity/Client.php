<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ClientRepository;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\Common\Collections\Collection;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;

/**
 * @ORM\Entity(repositoryClass=ClientRepository::class)
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 * @ApiResource(
 *      normalizationContext   ={"groups"={"client:read"}},
 *      denormalizationContext   ={"groups"={"client:wrie"}},
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN_AGENCE' or 'ROLE_USER_AGENCE' )",
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
     * @Groups({
     *          "trans:read", "trans:write",
     *          "client:read", "client:write"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({
     *           "trans:read", "trans:write",
     *           "client:read", "client:write"
     * })
     * 
     */
    private $archive = 0;

 
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du client est obligatoire")
     * @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $nomClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le nom du beneficiaire est obligatoire")
     *  @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $nomBeneficiaire;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le cni du client est obligatoire")
     * @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $cniClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $cniBeneficiaire;

    /**
     *  @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero du client est obligatoire")
     *   @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $phoneClient;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Le numero du beneficiaire est obligatoire")
     *   @Groups({
     *      "client:read", "client:write",
     *      "trans:read", "trans:write"
     * })
     */
    private $phoneBeneficiaire;

    /**
     * @ORM\OneToOne(targetEntity=Transaction::class, mappedBy="clientTrans",  cascade={"persist", "remove"})
     * @Groups({
     *      "trans:read","trans:write",
     *      "client:read", "client:write",
     * 
     * })
     */
    private $transaction;


    public function __construct()
    {
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

    public function getTransaction(): ?Transaction
    {
        return $this->transaction;
    }

    public function setTransaction(?Transaction $transaction): self
    {
        // unset the owning side of the relation if necessary
        if ($transaction === null && $this->transaction !== null) {
            $this->transaction->setClientTrans(null);
        }

        // set the owning side of the relation if necessary
        if ($transaction !== null && $transaction->getClientTrans() !== $this) {
            $transaction->setClientTrans($this);
        }

        $this->transaction = $transaction;

        return $this;
    }


}
