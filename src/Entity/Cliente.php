<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('cliente')]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups('cliente')]
    private string $nome;

    #[ORM\Column(length: 14)]
    #[Groups('cliente')]
    private string $cpf;

    #[ORM\OneToMany(mappedBy: 'cliente', targetEntity: Carrinho::class, cascade: ['persist', 'remove'])]
    private Collection $carrinhos;

    #[ORM\Column]
    private ?bool $ativo = null;

    public function __construct()
    {
        $this->carrinhos = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): static
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): static
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getCarrinhos(): Collection
    {
        return $this->carrinhos;
    }

    public function addCarrinho(Carrinho $carrinho): static
    {
        if (!$this->carrinhos->contains($carrinho)) {
            $this->carrinhos[] = $carrinho;
            $carrinho->setCliente($this);
        }

        return $this;
    }

    public function removeCarrinho(Carrinho $carrinho): static
    {
        if ($this->carrinhos->contains($carrinho)) {
            $this->carrinhos->removeElement($carrinho);
            if ($carrinho->getCliente() === $this) {
                $carrinho->setCliente(null);
            }
        }

        return $this;
    }

    public function isAtivo(): ?bool
    {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): static
    {
        $this->ativo = $ativo;

        return $this;
    }
}
