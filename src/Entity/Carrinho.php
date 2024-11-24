<?php

namespace App\Entity;

use App\Repository\CarrinhoRepository;
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

    #[ORM\OneToOne(inversedBy: 'carrinho', cascade: ['persist', 'remove'])]
    private Cliente $clienteId;

    #[ORM\ManyToOne(inversedBy: 'carrinhos')]
    private User $usuarioId;

    #[ORM\Column(length: 255)]
    private string $status;

    #[ORM\Column]
    private int $valorTotal;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $criadoEm;

    #[ORM\Column(type: Types::DATETIME_MUTABLE,nullable: true)]
    private ?\DateTimeInterface $atualizadoEm = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $finalizadoEm = null;

    /**
     * @var Collection<int, Item>
     */
    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'carrinho_id')]
    private Collection $items;

    public function __construct($cliente,$usuario,$status = "Aguardando pagamento",$valorTotal = 0)
    {
        $this->clienteId = $cliente;
        $this->usuarioId = $usuario;
        $this->status = $status;
        $this->valorTotal = $valorTotal;
        $this->criadoEm = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClienteId(): ?Cliente
    {
        return $this->clienteId;
    }

    public function setClienteId(?Cliente $clienteId): static
    {
        $this->clienteId = $clienteId;

        return $this;
    }

    public function getUsuarioId(): ?User
    {
        return $this->usuarioId;
    }

    public function setUsuarioId(?User $usuarioId): static
    {
        $this->usuarioId = $usuarioId;

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
        return $this->valorTotal;
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

    public function setAtualizadoEm(\DateTimeInterface $atualizadoEm): static
    {
        $this->atualizadoEm = $atualizadoEm;

        return $this;
    }

    public function getFinalizadoEm(): ?\DateTimeInterface
    {
        return $this->finalizadoEm;
    }

    public function setFinalizadoEm(\DateTimeInterface $finalizadoEm): static
    {
        $this->finalizadoEm = $finalizadoEm;

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
