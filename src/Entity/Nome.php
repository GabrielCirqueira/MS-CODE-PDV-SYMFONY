<?php

namespace App\Entity;

use App\Repository\NomeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NomeRepository::class)]
class Nome
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $quantidade = null;

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
}
