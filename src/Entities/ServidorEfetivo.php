<?php
// src/Entities/ServidorEfetivo.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'servidor_efetivo')]
class ServidorEfetivo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'se_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pes_id', referencedColumnName: 'pes_id')]
    private ?Pessoa $pessoa = null;

    #[ORM\Column(name: 'se_matricula', type: 'string', length: 20)]
    private string $matricula;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getPessoa(): Pessoa { return $this->pessoa; }
    public function setPessoa(Pessoa $pessoa): self { $this->pessoa = $pessoa; return $this; }
    public function getMatricula(): string { return $this->matricula; }
    public function setMatricula(string $matricula): self { $this->matricula = $matricula; return $this; }
}
