<?php
// src/Entities/FotoPessoa.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'foto_pessoa')]
class FotoPessoa
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'fp_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(targetEntity: 'Pessoa', inversedBy: 'foto')]
    #[ORM\JoinColumn(name: 'pes_id', referencedColumnName: 'pes_id')]
    private Pessoa $pessoa;

    #[ORM\Column(name: 'fp_data', type: 'date')]
    private \DateTimeInterface $data;

    #[ORM\Column(name: 'fp_bucket', type: 'string', length: 50)]
    private string $bucket;

    #[ORM\Column(name: 'fp_hash', type: 'string', length: 50)]
    private string $hash;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getPessoa(): Pessoa { return $this->pessoa; }
    public function setPessoa(Pessoa $pessoa): self { $this->pessoa = $pessoa; return $this; }
    public function getData(): \DateTimeInterface { return $this->data; }
    public function setData(\DateTimeInterface $data): self { $this->data = $data; return $this; }
    public function getBucket(): string { return $this->bucket; }
    public function setBucket(string $bucket): self { $this->bucket = $bucket; return $this; }
    public function getHash(): string { return $this->hash; }
    public function setHash(string $hash): self { $this->hash = $hash; return $this; }
}