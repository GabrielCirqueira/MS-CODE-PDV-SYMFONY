<?php

namespace App\Entity;

use App\Repository\ProdutoRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProdutoRepository::class)]
class Produto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nome = null;

    #[ORM\Column]
    private ?int $quantidade = null;

    #[ORM\Column]
    private ?int $valor_unitario = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $criado_em = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $atualizado_em = null;

    #[ORM\ManyToOne]
    private ?Categoria $categoria_id = null;

    #[ORM\ManyToOne(inversedBy: 'produto_id')]
    private ?Item $item = null;

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

    public function getQuantidade(): ?int
    {
        return $this->quantidade;
    }

    public function setQuantidade(int $quantidade): static
    {
        $this->quantidade = $quantidade;

        return $this;
    }

    public function getValorUnitario(): ?int
    {
        return $this->valor_unitario;
    }

    public function setValorUnitario(int $valor_unitario): static
    {
        $this->valor_unitario = $valor_unitario;

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

    public function getCategoriaId(): ?Categoria
    {
        return $this->categoria_id;
    }

    public function setCategoriaId(?Categoria $categoria_id): static
    {
        $this->categoria_id = $categoria_id;

        return $this;
    }

    public function getItem(): ?Item
    {
        return $this->item;
    }

    public function setItem(?Item $item): static
    {
        $this->item = $item;

        return $this;
    }
}
