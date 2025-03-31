<?php

namespace PSS0225Seplag\Config;

class DBConfig
{
	private static ?string $host = null;
	private static ?int $port = null;
	private static ?string $dbname = null;
	private static ?string $user = null;
	private static ?string $password = null;
	private static bool $loaded = false;

	private function __construct()
	{
		// private
	}

	private static function load(): void
	{
		if (self::$loaded) {
			return;
		}

		self::$host = self::getEnvWithDefault('DB_HOST', 'localhost');
		self::$port = (int)self::getEnvWithDefault('DB_PORT', '5432');
		self::$dbname = self::getEnvWithDefault('DB_NAME', 'app_pss_seplag');
		self::$user = self::getEnvWithDefault('DB_USER', 'pss_user_sistema');
		self::$password = self::getEnvWithDefault('DB_PASSWORD', 'PsSS3cr#t022025');

		self::$loaded = true;
	}

	private static function getEnvWithDefault(string $var, string $default): string
	{
		//$value = getenv($var);
		$value = $_ENV[$var] ?? getenv($var);
		return $value !== false ? $value : $default;
	}

	public static function getUser(): string
	{
		self::load();
		return self::$user ?? throw new \RuntimeException('usuario nao configurado');
	}

	public static function getPass(): string
	{
		self::load();
		return self::$password ?? throw new \RuntimeException('pwd nao configurada');
	}

	public static function pgDsn(): string
	{
		self::load();

		return sprintf(
			'pgsql:host=%s;port=%d;dbname=%s',
			self::$host ?? throw new \RuntimeException('host nao configurado'),
			self::$port ?? throw new \RuntimeException('porta nao configurada'),
			self::$dbname ?? throw new \RuntimeException('nome do banco nao configurado')
		);
	}
}
