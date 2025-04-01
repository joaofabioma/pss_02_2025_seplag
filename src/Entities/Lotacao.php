<?php
// src/Entities/Lotacao.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'lotacao')]
class Lotacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'lot_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa', inversedBy: 'lotacoes')]
    #[ORM\JoinColumn(name: 'pes_id', referencedColumnName: 'pes_id', nullable: true)]
    private ?Pessoa $pessoa = null;

    #[ORM\ManyToOne(targetEntity: 'Unidade', inversedBy: 'lotacoes')]
    #[ORM\JoinColumn(name: 'unid_id', referencedColumnName: 'unid_id', nullable: true)]
    private ?Unidade $unidade = null;

    #[ORM\Column(name: 'lot_data_lotacao', type: 'date')]
    private \DateTimeInterface $dataLotacao;

    #[ORM\Column(name: 'lot_data_remocao', type: 'date', nullable: true)]
    private ?\DateTimeInterface $dataRemocao = null;

    #[ORM\Column(name: 'lot_portaria', type: 'string', length: 100)]
    private string $portaria;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getPessoa(): Pessoa { return $this->pessoa; }
    public function setPessoa(Pessoa $pessoa): self { $this->pessoa = $pessoa; return $this; }
    public function getUnidade(): Unidade { return $this->unidade; }
    public function setUnidade(Unidade $unidade): self { $this->unidade = $unidade; return $this; }
    public function getDataLotacao(): \DateTimeInterface { return $this->dataLotacao; }
    public function setDataLotacao(\DateTimeInterface $dataLotacao): self { $this->dataLotacao = $dataLotacao; return $this; }
    public function getDataRemocao(): ?\DateTimeInterface { return $this->dataRemocao; }
    public function setDataRemocao(?\DateTimeInterface $dataRemocao): self { $this->dataRemocao = $dataRemocao; return $this; }
    public function getPortaria(): string { return $this->portaria; }
    public function setPortaria(string $portaria): self { $this->portaria = $portaria; return $this; }
}