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
    private int $quantidade;

    #[ORM\Column]
    private int $valor;

    #[ORM\ManyToOne]
    private Produto $produto;

    #[ORM\ManyToOne]
    private Carrinho $carrinho;
    public function __construct(Produto $produto, Carrinho $carrinho, int $quantidade, int $valor)
    {        
        $this->setProduto($produto);
        $this->setCarrinho($carrinho);
        $this->setQuantidade($quantidade);
        $this->setValor($valor);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): static
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getValor(): ?int
    {
        return $this->valor;
    }

    public function setValor(int $valor): static
    {
        $this->valor = $valor;

        return $this;
    }

    /**
     * @return Collection<int, Produto>
     */
    public function setProduto(Produto $produto): static
    {
        $this->produto = $produto;

        return $this;
    }

    public function getProduto(): Produto
    {
        return $this->produto;
    }

    public function setCarrinho(Carrinho $carrinho): static
    {
        $this->carrinho = $carrinho;

        return $this;
    }

    public function getCarrinho(): Carrinho
    {
        return $this->carrinho;
    }



}
