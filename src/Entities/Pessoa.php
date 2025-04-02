<?php
// src/Entities/Pessoa.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: 'pessoa')]
class Pessoa
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'SEQUENCE')]
    #[ORM\Column(name: 'pes_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'pes_nome', type: 'string', length: 100)]
    private string $nome;

    #[ORM\Column(name: 'pes_data_nascimento', type: 'date')]
    private \DateTimeInterface $dataNascimento;

    #[ORM\Column(name: 'pes_sexo', type: 'string', length: 9)]
    private string $sexo;

    #[ORM\Column(name: 'pes_mae', type: 'string', length: 200)]
    private string $mae;

    #[ORM\Column(name: 'pes_pai', type: 'string', length: 200)]
    private string $pai;

    #[ORM\OneToOne(targetEntity: 'FotoPessoa', mappedBy: 'pessoa')]
    private ?FotoPessoa $foto = null;

    #[ORM\ManyToMany(targetEntity: Endereco::class, inversedBy: 'pessoas')]
    #[ORM\JoinTable(name: 'pessoa_endereco')]
    #[ORM\JoinColumn(name: 'pes_id', referencedColumnName: 'pes_id')]
    #[ORM\InverseJoinColumn(name: 'end_id', referencedColumnName: 'end_id')]
    private Collection $enderecos;

    #[ORM\OneToMany(targetEntity: ServidorTemporario::class, mappedBy: 'pessoa')]
    private ?ServidorTemporario $servidorTemporario = null;

    #[ORM\OneToMany(targetEntity: ServidorEfetivo::class, mappedBy: 'pessoa')]
    private ?ServidorEfetivo $servidorEfetivo = null;

    #[ORM\OneToMany(targetEntity: Lotacao::class, mappedBy: 'pessoa')]
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
    public function getDataNascimento(): \DateTimeInterface
    {
        return $this->dataNascimento;
    }
    public function setDataNascimento(\DateTimeInterface $dataNascimento): self
    {
        $this->dataNascimento = $dataNascimento;
        return $this;
    }
    public function getSexo(): string
    {
        return $this->sexo;
    }
    public function setSexo(string $sexo): self
    {
        $this->sexo = $sexo;
        return $this;
    }
    public function getMae(): string
    {
        return $this->mae;
    }
    public function setMae(string $mae): self
    {
        $this->mae = $mae;
        return $this;
    }
    public function getPai(): string
    {
        return $this->pai;
    }
    public function setPai(string $pai): self
    {
        $this->pai = $pai;
        return $this;
    }
    public function getFoto(): ?FotoPessoa
    {
        return $this->foto;
    }
    public function setFoto(?FotoPessoa $foto): self
    {
        $this->foto = $foto;
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
    public function getServidorTemporario(): ?ServidorTemporario
    {
        return $this->servidorTemporario;
    }
    public function setServidorTemporario(?ServidorTemporario $servidorTemporario): self
    {
        $this->servidorTemporario = $servidorTemporario;
        return $this;
    }
    public function getServidorEfetivo(): ?ServidorEfetivo
    {
        return $this->servidorEfetivo;
    }
    public function setServidorEfetivo(?ServidorEfetivo $servidorEfetivo): self
    {
        $this->servidorEfetivo = $servidorEfetivo;
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
            $lotacao->setPessoa($this);
        }
        return $this;
    }
    public function removeLotacao(Lotacao $lotacao): self
    {
        if ($this->lotacoes->removeElement($lotacao)) {
            if ($lotacao->getPessoa() === $this) {
                $lotacao->setPessoa(null);
            }
        }
        return $this;
    }
}
