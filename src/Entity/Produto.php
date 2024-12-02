<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use JsonSerializable;

#[ORM\Entity]
class Produto implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups('produto')]
    private int $id;

    #[ORM\Column(length: 255)]
    #[Groups('produto')]
    private string $nome;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('produto')]
    private ?string $descricao = null;

    #[ORM\Column]
    #[Groups('produto')]
    private int $quantidade;

    #[ORM\Column]
    #[Groups('produto')]
    private int $valorUnitario;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups('produto')]
    private \DateTimeInterface $criadoEm;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    #[Groups('produto')]
    private ?\DateTimeInterface $atualizadoEm = null;

    #[ORM\ManyToOne]
    #[Groups('produto')]
    private ?Categoria $categoria = null;


    public function __construct()
    {
        $this->criadoEm = new \DateTimeImmutable('now', new \DateTimeZone('America/Sao_Paulo'));
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

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(?string $descricao): static
    {
        $this->descricao = $descricao;

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
        return $this->valorUnitario / 100;
    }

    public function setValorUnitario(int $valorUnitario): static
    {
        $this->valorUnitario = $valorUnitario;

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

    public function getCategoria(): ?Categoria
    {
        return $this->categoria;
    }

    public function setCategoria(?Categoria $categoria): static
    {
        $this->categoria = $categoria;

        return $this;
    }


    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'nome' => $this->getNome(),
            'descricao' => $this->getDescricao(),
            'quantidade' => $this->getQuantidade(),
            'valorUnitario' => $this->getValorUnitario(),
            'criadoEm' => $this->getCriadoEm(),
            'atualizadoEm' => $this->getAtualizadoEm(),
            'categoria' => $this->getCategoria()?->getNome(),
        ];
    }
}
