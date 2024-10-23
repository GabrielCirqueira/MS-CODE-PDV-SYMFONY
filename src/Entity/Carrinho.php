<?php

namespace App\Entity;

use App\Repository\CarrinhoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarrinhoRepository::class)]
class Carrinho
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'carrinho', cascade: ['persist', 'remove'])]
    private ?Cliente $cliente_id = null;

    #[ORM\ManyToOne(inversedBy: 'carrinhos')]
    private ?User $usuario_id = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $valor_total = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $criado_em = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $atualizado_em = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $finalizado_em = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'carrinho_id')]
    private Collection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClienteId(): ?Cliente
    {
        return $this->cliente_id;
    }

    public function setClienteId(?Cliente $cliente_id): static
    {
        $this->cliente_id = $cliente_id;

        return $this;
    }

    public function getUsuarioId(): ?User
    {
        return $this->usuario_id;
    }

    public function setUsuarioId(?User $usuario_id): static
    {
        $this->usuario_id = $usuario_id;

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
        return $this->valor_total;
    }

    public function setValorTotal(int $valor_total): static
    {
        $this->valor_total = $valor_total;

        return $this;
    }

    public function getCriadoEm(): ?\DateTimeInterface
    {
        return $this->criado_em;
    }

    public function setCriadoEm(\DateTimeInterface $criado_em): static
    {
        $this->criado_em = $criado_em;

        return $this;
    }

    public function getAtualizadoEm(): ?\DateTimeInterface
    {
        return $this->atualizado_em;
    }

    public function setAtualizadoEm(\DateTimeInterface $atualizado_em): static
    {
        $this->atualizado_em = $atualizado_em;

        return $this;
    }

    public function getFinalizadoEm(): ?\DateTimeInterface
    {
        return $this->finalizado_em;
    }

    public function setFinalizadoEm(\DateTimeInterface $finalizado_em): static
    {
        $this->finalizado_em = $finalizado_em;

        return $this;
    }

    /**
     * @return Collection<int, Item>
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): static
    {
        if (!$this->items->contains($item)) {
            $this->items->add($item);
            $item->addCarrinhoId($this);
        }

        return $this;
    }

    public function removeItem(Item $item): static
    {
        if ($this->items->removeElement($item)) {
            $item->removeCarrinhoId($this);
        }

        return $this;
    }
}
