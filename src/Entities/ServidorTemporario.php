<?php
// src/Entities/ServidorTemporario.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'servidor_temporario')]
class ServidorTemporario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'st_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: 'Pessoa')]
    #[ORM\JoinColumn(name: 'pes_id', referencedColumnName: 'pes_id')]
    private ?Pessoa $pessoa = null;

    #[ORM\Column(name: 'st_data_admissao', type: 'date')]
    private \DateTimeInterface $dataAdmissao;

    #[ORM\Column(name: 'st_data_demissao', type: 'date', nullable: true)]
    private ?\DateTimeInterface $dataDemissao = null;

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getPessoa(): Pessoa { return $this->pessoa; }
    public function setPessoa(Pessoa $pessoa): self { $this->pessoa = $pessoa; return $this; }
    public function getDataAdmissao(): \DateTimeInterface { return $this->dataAdmissao; }
    public function setDataAdmissao(\DateTimeInterface $dataAdmissao): self { $this->dataAdmissao = $dataAdmissao; return $this; }
    public function getDataDemissao(): ?\DateTimeInterface { return $this->dataDemissao; }
    public function setDataDemissao(?\DateTimeInterface $dataDemissao): self { $this->dataDemissao = $dataDemissao; return $this; }
}