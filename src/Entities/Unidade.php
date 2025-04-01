<?php
// src/Entities/Unidade.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'unidade')]
class Unidade
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'unid_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'unid_nome', type: 'string', length: 200)]
    private string $nome;

    #[ORM\Column(name: 'unid_sigla', type: 'string', length: 20)]
    private string $sigla;

    #[ORM\ManyToMany(targetEntity: 'Endereco', inversedBy: 'unidades')]
    #[ORM\JoinTable(name: 'unidade_endereco')]
    #[ORM\JoinColumn(name: 'unid_id', referencedColumnName: 'unid_id')]
    #[ORM\InverseJoinColumn(name: 'end_id', referencedColumnName: 'end_id')]
    private Collection $enderecos;

    #[ORM\OneToMany(targetEntity: 'Lotacao', mappedBy: 'unidade')]
    private Collection $lotacoes;

    public function __construct()
    {
        $this->enderecos = new ArrayCollection();
        $this->lotacoes = new ArrayCollection();
    }

    // Getters and Setters
    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNome(): string
    {
        return $this->nome;
    }
    public function setNome(string $nome): self
    {
        $this->nome = $nome;
        return $this;
    }
    public function getSigla(): string
    {
        return $this->sigla;
    }
    public function setSigla(string $sigla): self
    {
        $this->sigla = $sigla;
        return $this;
    }
    public function getEnderecos(): Collection
    {
        return $this->enderecos;
    }
    public function addEndereco(Endereco $endereco): self
    {
        if (!$this->enderecos->contains($endereco)) {
            $this->enderecos[] = $endereco;
        }
        return $this;
    }
    public function removeEndereco(Endereco $endereco): self
    {
        $this->enderecos->removeElement($endereco);
        return $this;
    }
    public function getLotacoes(): Collection
    {
        return $this->lotacoes;
    }
    public function addLotacao(Lotacao $lotacao): self
    {
        if (!$this->lotacoes->contains($lotacao)) {
            $this->lotacoes[] = $lotacao;
            $lotacao->setUnidade($this);
        }
        return $this;
    }
    public function removeLotacao(Lotacao $lotacao): self
    {
        if ($this->lotacoes->removeElement($lotacao)) {
            if ($lotacao->getUnidade() === $this) {
                $lotacao->setUnidade(null);
            }
        }
        return $this;
    }
}
