<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Cliente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $nome;

    #[ORM\Column(length: 14)]
    private string $cpf;

    #[ORM\OneToOne(mappedBy: 'cliente_id', cascade: ['persist', 'remove'])]
    private ?Carrinho $carrinho = null;

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

    public function getCarrinho(): ?Carrinho
    {
        return $this->carrinho;
    }

    public function setCarrinho(?Carrinho $carrinho): static
    {
        // unset the owning side of the relation if necessary
        if ($carrinho === null && $this->carrinho !== null) {
            $this->carrinho->setClienteId(null);
        }

        // set the owning side of the relation if necessary
        if ($carrinho !== null && $carrinho->getClienteId() !== $this) {
            $carrinho->setClienteId($this);
        }

        $this->carrinho = $carrinho;

        return $this;
    }
}
