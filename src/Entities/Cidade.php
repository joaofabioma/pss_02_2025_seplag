<?php
// src/Entities/Cidade.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'cidade')]
class Cidade
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'cid_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'cid_nome', type: 'string', length: 200)]
    private string $nome;

    #[ORM\Column(name: 'cid_uf', type: 'string', length: 2)]
    private string $uf;

    #[ORM\OneToMany(targetEntity: 'Endereco', mappedBy: 'cidade')]
    private Collection $enderecos;

    public function __construct()
    {
        $this->enderecos = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int { return $this->id; }
    public function getNome(): string { return $this->nome; }
    public function setNome(string $nome): self { $this->nome = $nome; return $this; }
    public function getUf(): string { return $this->uf; }
    public function setUf(string $uf): self { $this->uf = $uf; return $this; }
    public function getEnderecos(): Collection { return $this->enderecos; }
    public function addEndereco(Endereco $endereco): self { if (!$this->enderecos->contains($endereco)) { $this->enderecos[] = $endereco; $endereco->setCidade($this); } return $this; }
    public function removeEndereco(Endereco $endereco): self { if ($this->enderecos->removeElement($endereco)) { if ($endereco->getCidade() === $this) { $endereco->setCidade(null); } } return $this; }
}