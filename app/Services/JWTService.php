<?php
namespace App\Services;

// require "vendor/autoload.php";

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use App\Models\User;

class JWTService
{
	private $access_token_private_key;
	private $access_token_public_key;
	private $refresh_token_private_key;
	private $refresh_token_public_key;
	private $issuer;

	private const ACCESS_TOKEN_ALGORITHM = 'ES256';
	private const REFRESH_TOKEN_ALGORITHM = 'ES256';
	private const ACCESS_TOKEN_DURATION = 3600; // 1 hour in seconds
	private const REFRESH_TOKEN_DURATION = 604800; // 1 week in seconds

	public function __construct()
	{
		// $this->access_token_private_key = env("ACCESS_TOKEN_PRIVATE_KEY");
		// $this->access_token_public_key = env("ACCESS_TOKEN_PUBLIC_KEY");
		// $this->refresh_token_private_key = env("REFRESH_TOKEN_PRIVATE_KEY");
		// $this->refresh_token_public_key = env("REFRESH_TOKEN_PUBLIC_KEY");
		// $this->issuer = env("APP_URL");

		$this->issuer = "localhost:8000";
		$this->access_token_private_key = '-----BEGIN EC PRIVATE KEY-----
MHcCAQEEIDBxb24h66QEyCp7LFXA3dgrnhZgLPdlmcqbNxvyGku9oAoGCCqGSM49
AwEHoUQDQgAEB6OIdxR3MykPG0NsaX6z3UZkxhTyMAGE0VTYqpMcnS3a8d5IFB66
QhrY9bUXIznq4uy7nWGcwx+538vDwBB4Fw==
-----END EC PRIVATE KEY-----';
		$this->access_token_public_key = '-----BEGIN PUBLIC KEY-----
MFkwEwYHKoZIzj0CAQYIKoZIzj0DAQcDQgAEB6OIdxR3MykPG0NsaX6z3UZkxhTy
MAGE0VTYqpMcnS3a8d5IFB66QhrY9bUXIznq4uy7nWGcwx+538vDwBB4Fw==
-----END PUBLIC KEY-----';
		$this->refresh_token_private_key = '-----BEGIN EC PRIVATE KEY-----
MIGkAgEBBDDUb5BczQ9fESThWeGVJbPRHC8yTUntAe2JroxRNeVclKbvlM+eLlpz
5VQlHqtIIlWgBwYFK4EEACKhZANiAARIpOmVpPaNaLm549DVBxeXZuBDXPwz85wl
MAQPEFrTgIiEDw0bz9bq/PZ5wk6Syl/z2R6m9LS1luXWiTmluHaHFIlKH3ChV+jr
9iNLTR4nXAQZf8NOoYFwkwrDg9+XusM=
-----END EC PRIVATE KEY-----';
		$this->refresh_token_public_key = '-----BEGIN PUBLIC KEY-----
MHYwEAYHKoZIzj0CAQYFK4EEACIDYgAESKTplaT2jWi5uePQ1QcXl2bgQ1z8M/Oc
JTAEDxBa04CIhA8NG8/W6vz2ecJOkspf89kepvS0tZbl1ok5pbh2hxSJSh9woVfo
6/YjS00eJ1wEGX/DTqGBcJMKw4Pfl7rD
-----END PUBLIC KEY-----';
	}

	protected function generateToken(User $user, $secret_key, $durationSecs, $algorithm): string
	{
		$issuedAt = time();
		$expire = $issuedAt + $durationSecs; // Token valid for 1 hour
		$audience = [(string) $user->id];

		$payload = [
			"iss" => $this->issuer,
			"aud" => $audience,
			"iat" => $issuedAt,
			"exp" => $expire,
			"user" => $user,
		];

		$jwt = JWT::encode($payload, $secret_key, $algorithm);

		return $jwt;
	}

	public function generateAccessToken(User $user): string
	{
		return $this->generateToken(
			$user,
			$this->access_token_private_key ?? $this->generateNewAccessTokenKey(),
			self::ACCESS_TOKEN_DURATION,
			self::ACCESS_TOKEN_ALGORITHM,
		);
	}

	public function generateRefreshToken(User $user): string
	{
		return $this->generateToken(
			$user,
			$this->refresh_token_private_key ?? $this->generateNewRefreshTokenKey(),
			self::REFRESH_TOKEN_DURATION,
			self::REFRESH_TOKEN_ALGORITHM,
		);
	}

	protected function validateToken($token, $public_key, $algorithm)
	{
		try {
			$decoded = JWT::decode($token, keyOrKeyArray: new Key($public_key, $algorithm));
			return $decoded;
		} catch (\Exception $e) {
			return false;
		}
	}

	public function validateAccessToken($token)
	{
		return $this->validateToken($token, $this->access_token_public_key, env("ACCESS_TOKEN_ALGORITHM", "ES256"));
	}

	public function validateRefreshToken($token)
	{
		return $this->validateToken($token, $this->refresh_token_public_key, env("REFRESH_TOKEN_ALGORITHM", "ES256"));
	}

	private function generateNewRefreshTokenKey()
	{
		// Implement key generation logic here
		return 'generated_key';
	}

	private function generateNewAccessTokenKey()
	{
		// Implement key generation logic here
		return 'generated_key';
	}

	public function accessTokenTTLSeconds()
	{
		return self::ACCESS_TOKEN_DURATION;
	}

	public function refreshTokenTTLSeconds()
	{
		return self::REFRESH_TOKEN_DURATION;
	}
}

// 
// $jwtService = new JWTService();

// // Simple test case for generateAccessToken
// $user = new User();
// $user->id = 123;

// Generate access token
// $accessToken = $jwtService->generateAccessToken($user);

// // Validate access token
// $claims = $jwtService->validateAccessToken($accessToken);

// // Check result
// if ($claims) {
// 	echo "Test passed: Access token is valid.\n";
// 	echo "Access token user:\n";
// 	print_r($claims);
// } else {
// 	echo "Test failed: Access token is invalid.\n";
// }

// You can uncomment and run the following to test generateRefreshToken separately:

// Simple test case for generateRefreshToken

// Generate refresh token
// $refreshToken = $jwtService->generateRefreshToken($user);

// Validate refresh token
// $validatedRefreshToken = $jwtService->validateRefreshToken($refreshToken);

// Check result
// if ($validatedRefreshToken) {
//     echo "Test passed: Refresh token is valid.\n";
//     echo "Refresh token user ID: " . $validatedRefreshToken->user->id . "\n";
// } else {
//     echo "Test failed: Refresh token is invalid.\n";
// }
