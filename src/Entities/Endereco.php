<?php
// src/Entities/Endereco.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity]
#[ORM\Table(name: 'endereco')]
class Endereco
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'end_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'end_tipo_logradouro', type: 'string', length: 50)]
    private string $tipoLogradouro;

    #[ORM\Column(name: 'end_logradouro', type: 'string', length: 200)]
    private string $logradouro;

    #[ORM\Column(name: 'end_numero', type: 'integer')]
    private int $numero;

    #[ORM\Column(name: 'end_bairro', type: 'string', length: 100)]
    private string $bairro;

    #[ORM\ManyToOne(targetEntity: 'Cidade', inversedBy: 'enderecos')]
    #[ORM\JoinColumn(name: 'cid_id', referencedColumnName: 'cid_id')]
    private Cidade $cidade;

    #[ORM\ManyToMany(targetEntity: 'Pessoa', mappedBy: 'enderecos')]
    private Collection $pessoas;

    #[ORM\ManyToMany(targetEntity: 'Unidade', mappedBy: 'enderecos')]
    private Collection $unidades;

    public function __construct()
    {
        $this->pessoas = new ArrayCollection();
        $this->unidades = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getTipoLogradouro(): string { return $this->tipoLogradouro; }
    public function setTipoLogradouro(string $tipoLogradouro): self { $this->tipoLogradouro = $tipoLogradouro; return $this; }
    public function getLogradouro(): string { return $this->logradouro; }
    public function setLogradouro(string $logradouro): self { $this->logradouro = $logradouro; return $this; }
    public function getNumero(): int { return $this->numero; }
    public function setNumero(int $numero): self { $this->numero = $numero; return $this; }
    public function getBairro(): string { return $this->bairro; }
    public function setBairro(string $bairro): self { $this->bairro = $bairro; return $this; }
    public function getCidade(): Cidade { return $this->cidade; }
    public function setCidade(Cidade $cidade): self { $this->cidade = $cidade; return $this; }
    public function getPessoas(): Collection { return $this->pessoas; }
    public function addPessoa(Pessoa $pessoa): self { if (!$this->pessoas->contains($pessoa)) { $this->pessoas[] = $pessoa; $pessoa->addEndereco($this); } return $this; }
    public function removePessoa(Pessoa $pessoa): self { if ($this->pessoas->removeElement($pessoa)) { $pessoa->removeEndereco($this); } return $this; }
    public function getUnidades(): Collection { return $this->unidades; }
    public function addUnidade(Unidade $unidade): self { if (!$this->unidades->contains($unidade)) { $this->unidades[] = $unidade; $unidade->addEndereco($this); } return $this; }
    public function removeUnidade(Unidade $unidade): self { if ($this->unidades->removeElement($unidade)) { $unidade->removeEndereco($this); } return $this; }
}