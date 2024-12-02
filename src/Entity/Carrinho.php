<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Carrinho
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\ManyToOne(inversedBy: 'carrinhos', cascade: ['persist', 'remove'])]
    private ?Cliente $cliente = null;

    #[ORM\ManyToOne(inversedBy: 'carrinhos')]
    private ?User $usuario = null;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column(nullable: true)]
    private ?int $valorTotal = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $criadoEm;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $atualizadoEm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $finalizadoEm = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'carrinhos')]
    private Collection $itens;

    public function __construct($cliente, $usuario)
    {   
        $this->cliente = $cliente;
        $this->usuario = $usuario;
        $this->status = "Pendente";
        $this->itens = new ArrayCollection();
        $this->criadoEm = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setCliente(?Cliente $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getUsuario(): ?User
    {
        return $this->usuario;
    }

    public function setUsuario(?User $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getValorTotal(): ?int
    {
        return $this->valorTotal / 100;
    }

    public function setValorTotal(int $valorTotal): static
    {
        $this->valorTotal = $valorTotal;

        return $this;
    }

    public function getCriadoEm(): ?\DateTimeInterface
    {
        return $this->criadoEm;
    }

    public function setCriadoEm(\DateTimeInterface $criadoEm): static
    {
        $this->criadoEm = $criadoEm;

        return $this;
    }

    public function getAtualizadoEm(): ?\DateTimeInterface
    {
        return $this->atualizadoEm;
    }

    public function setAtualizadoEm(?\DateTimeInterface $atualizadoEm): static
    {
        $this->atualizadoEm = $atualizadoEm;

        return $this;
    }

    public function getFinalizadoEm(): ?\DateTimeInterface
    {
        return $this->finalizadoEm;
    }

    public function setFinalizadoEm(?\DateTimeInterface $finalizadoEm): static
    {
        $this->finalizadoEm = $finalizadoEm;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItens(): Collection
    {
        return $this->itens;
    }

}
