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

    #[ORM\ManyToOne(inversedBy: 'carrinho', cascade: ['persist', 'remove'])]
    private Cliente $cliente;

    #[ORM\ManyToOne(inversedBy: 'carrinhos')]
    private User $usuario;

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
    #[ORM\ManyToMany(targetEntity: Item::class, mappedBy: 'carrinhos')]
    private Collection $items;

    public function __construct($cliente,$usuario,$status = "Aguardando pagamento",$valorTotal = 0)
    {
        $this->cliente = $cliente;
        $this->usuario = $usuario;
        $this->status = $status;
        $this->valorTotal = $valorTotal;
        $this->criadoEm = new \DateTimeImmutable();
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getcliente(): ?Cliente
    {
        return $this->cliente;
    }

    public function setcliente(?Cliente $cliente): static
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getusuario(): ?User
    {
        return $this->usuario;
    }

    public function setusuario(?User $usuario): static
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
