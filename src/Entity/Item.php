<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column]
    private int $quatidade;

    /**
     * @var Collection<int, Produto>
     */
    #[ORM\OneToMany(targetEntity: Produto::class, mappedBy: 'item')]
    private Collection $produtoId;

    /**
     * @var Collection<int, Carrinho>
     */
    #[ORM\ManyToMany(targetEntity: Carrinho::class, inversedBy: 'items')]
    private Collection $carrinhoId;

    public function __construct()
    {
        $this->produtoId = new ArrayCollection();
        $this->carrinhoId = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuatidade(): ?int
    {
        return $this->quatidade;
    }

    public function setQuatidade(int $quatidade): static
    {
        $this->quatidade = $quatidade;

        return $this;
    }

    /**
     * @return Collection<int, Produto>
     */
    public function getProdutoId(): Collection
    {
        return $this->produtoId;
    }

    public function addProdutoId(Produto $produtoId): static
    {
        if (!$this->produtoId->contains($produtoId)) {
            $this->produtoId->add($produtoId);
            $produtoId->setItem($this);
        }

        return $this;
    }

    public function removeProdutoId(Produto $produtoId): static
    {
        if ($this->produtoId->removeElement($produtoId)) {
            // set the owning side to null (unless already changed)
            if ($produtoId->getItem() === $this) {
                $produtoId->setItem(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Carrinho>
     */
    public function getCarrinhoId(): Collection
    {
        return $this->carrinhoId;
    }

    public function addCarrinhoId(Carrinho $carrinhoId): static
    {
        if (!$this->carrinhoId->contains($carrinhoId)) {
            $this->carrinhoId->add($carrinhoId);
        }

        return $this;
    }

    public function removeCarrinhoId(Carrinho $carrinhoId): static
    {
        $this->carrinhoId->removeElement($carrinhoId);

        return $this;
    }
}
