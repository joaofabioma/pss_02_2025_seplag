<?php
// src/Entities/User.php
namespace PSS0225Seplag\Entities;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'users')]
class Users
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'IDENTITY')]
    #[ORM\Column(name: 'us_id', type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(name: 'us_name', type: 'string', length: 200)]
    private string $nome;

    #[ORM\Column(name: 'us_password', type: 'string', length: 200)]
    private string $password;

    #[ORM\Column(name: 'us_role', type: 'string', length: 50, options: ['default' => 'user'])]
    private string $role;

    #[ORM\Column(name: 'us_email', type: 'string', length: 180, unique: true)]
    private string $email;
    
    #[ORM\Column(name: 'created_at', type: 'datetime', options: ['default' => 'CURRENT_TIMESTAMP'])]
    private \DateTimeInterface $createdat;

    // Getters and Setters
    public function getId(): ?int {return $this->id;}
    
    public function getNome(): string {return $this->nome;}
    public function setNome(string $nome): self {$this->nome = $nome; return $this; }
    
    public function getPassword(): string {return $this->password;}
    public function setPassword(string $password): self {$this->password = $password;return $this;}
    
    public function getEmail(): string {return $this->email;}
    public function setEmail(string $email): self {$this->email = $email;return $this;}
    
    public function getRole(): string {return $this->role;}
    public function setRole(string $role): self {$this->role = $role;return $this;}
    
    public function getCreatedat(): \DateTimeInterface {return $this->createdat;}
}