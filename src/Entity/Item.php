<?php

namespace App\Entity;

use App\Repository\ItemRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quatidade = null;

    /**
     * @var Collection<int, Produto>
     */
    #[ORM\OneToMany(targetEntity: Produto::class, mappedBy: 'item')]
    private Collection $produto_id;

    /**
     * @var Collection<int, Carrinho>
     */
    #[ORM\ManyToMany(targetEntity: Carrinho::class, inversedBy: 'items')]
    private Collection $carrinho_id;

    public function __construct()
    {
        $this->produto_id = new ArrayCollection();
        $this->carrinho_id = new ArrayCollection();
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
        return $this->produto_id;
    }

    public function addProdutoId(Produto $produtoId): static
    {
        if (!$this->produto_id->contains($produtoId)) {
            $this->produto_id->add($produtoId);
            $produtoId->setItem($this);
        }

        return $this;
    }

    public function removeProdutoId(Produto $produtoId): static
    {
        if ($this->produto_id->removeElement($produtoId)) {
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
        return $this->carrinho_id;
    }

    public function addCarrinhoId(Carrinho $carrinhoId): static
    {
        if (!$this->carrinho_id->contains($carrinhoId)) {
            $this->carrinho_id->add($carrinhoId);
        }

        return $this;
    }

    public function removeCarrinhoId(Carrinho $carrinhoId): static
    {
        $this->carrinho_id->removeElement($carrinhoId);

        return $this;
    }
}
