<?php

namespace App\Entity;

use App\Repository\CategoriaRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Categoria
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $nome;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $criadoEm;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable:true)]
    private ?\DateTimeInterface $atualizadoEm = null;

    public function __construct(){
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

}
