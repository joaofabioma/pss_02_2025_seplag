<?php

namespace PSS0225Seplag\Services;

use PSS0225Seplag\Models\User;
use PSS0225Seplag\Services\JWTService;

class AuthService
{
	private $userModel;
	private $jwtService;

	public function __construct($pdo)
	{
		$this->userModel = new User($pdo);
		$this->jwtService = new JWTService();
	}

	public function register($email, $password, $name, $role = 'user')
	{
		if ($this->userModel->findByEmail($email)) {
			throw new \Exception("Email ja registrado");
		}
		return $this->userModel->create($email, $password, $name, $role);
	}

	public function login($email, $password)
	{
		$user = $this->userModel->findByEmail($email);
		if (!$user || !password_verify($password, $user['us_password'])) {
			throw new \Exception("Credenciais Invalidas");
		}

		return $this->jwtService->generateToken([
			'id' => $user['us_id'],
			'email' => $user['us_email'],
			'role' => $user['us_role']
		]);
	}

	public function validateToken($token)
	{
		return $this->jwtService->validateToken($token);
	}
}
