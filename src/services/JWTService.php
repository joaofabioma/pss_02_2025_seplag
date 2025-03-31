<?php
namespace PSS0225Seplag\Services;

use PSS0225Seplag\Config\JwtConfig;

class JWTService
{
	private $secret;
	private $algorithm;
	private $expiration;

	public function __construct()
	{
		$config = JwtConfig::getConfig();
		$this->secret = base64_decode($config['secret']);
		$this->algorithm = $config['algorithm'];
		$this->expiration = $config['expiration'];
	}

	public function generateToken($payload)
	{
		$header = json_encode(['typ' => 'JWT', 'alg' => $this->algorithm]);
		$payload['exp'] = time() + $this->expiration;
		$payload = json_encode($payload);

		$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
		$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

		$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $this->secret, true);
		$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		return $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
	}

	public function validateToken($token)
	{
		$parts = explode('.', $token);
		if (count($parts) !== 3) return false;
		list($header, $payload, $signature) = $parts;

		$validSignature = hash_hmac('sha256', $header . "." . $payload, $this->secret, true);
		$validSignatureBase64 = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($validSignature));

		if (!hash_equals($validSignatureBase64, $signature)) return false;

		if ($signature !== $validSignatureBase64) return false;

		$decodedPayload = json_decode(base64_decode($payload), true);
		if (isset($decodedPayload['exp']) && $decodedPayload['exp'] < time()) return false;

		return $decodedPayload;
	}
}
