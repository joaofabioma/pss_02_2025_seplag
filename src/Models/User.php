<?php

namespace PSS0225Seplag\Models;

class User
{
	private $pdo;

	public function __construct($pdo)
	{
		$this->pdo = $pdo;
	}

	public function create($email, $password, $name, $role = 'user')
	{
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);
		$stmt = $this->pdo->prepare("INSERT INTO users (us_email, us_password, us_name, us_role) VALUES (?, ?, ?, ?)");
		return $stmt->execute([$email, $hashedPassword, $name, $role]);
	}

	public function findByEmail($email)
	{
		$stmt = $this->pdo->prepare("SELECT * FROM users WHERE us_email = ?");
		$stmt->execute([$email]);
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

}
