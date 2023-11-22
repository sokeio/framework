<?php

namespace Sokeio\Support\JWT;

use Sokeio\Exceptions\JWTException;

class JwtManager
{

    const ERROR_KEY_EMPTY        = 10;
    const ERROR_KEY_INVALID      = 12;
    const ERROR_ALGO_UNSUPPORTED = 20;
    const ERROR_ALGO_MISSING     = 22;
    const ERROR_INVALID_MAXAGE   = 30;
    const ERROR_INVALID_LEEWAY   = 32;
    const ERROR_JSON_FAILED      = 40;
    const ERROR_TOKEN_INVALID    = 50;
    const ERROR_TOKEN_EXPIRED    = 52;
    const ERROR_TOKEN_NOT_NOW    = 54;
    const ERROR_SIGNATURE_FAILED = 60;
    const ERROR_KID_UNKNOWN      = 70;

    /** @var array Supported Signing algorithms. */
    protected $algos = [
        'HS256' => 'sha256',
        'HS384' => 'sha384',
        'HS512' => 'sha512',
        'RS256' => \OPENSSL_ALGO_SHA256,
        'RS384' => \OPENSSL_ALGO_SHA384,
        'RS512' => \OPENSSL_ALGO_SHA512,
    ];

    /** @var string|resource The signature key. */
    protected $key;

    /** @var array The list of supported keys with id. */
    protected $keys = [];

    /** @var int|null Use setTestTimestamp() to set custom value for time(). Useful for testability. */
    protected $timestamp = null;

    /** @var string The JWT signing algorithm. Defaults to HS256. */
    protected $algo = 'HS256';

    /** @var int The JWT TTL in seconds. Defaults to 1 hour. */
    protected $maxAge = 3600;

    /** @var int Grace period in seconds to allow for clock skew. Defaults to 0 seconds. */
    protected $leeway = 0;

    /** @var string|null The passphrase for RSA signing (optional). */
    protected $passphrase;

    /**
     * Constructor.
     *
     * @param string|resource $key    The signature key. For RS* it should be file path or resource of private key.
     * @param string          $algo   The algorithm to sign/verify the token.
     * @param int             $maxAge The TTL of token to be used to determine expiry if `iat` claim is present.
     *                                This is also used to provide default `exp` claim in case it is missing.
     * @param int             $leeway Leeway for clock skew. Shouldnot be more than 2 minutes (120s).
     * @param string          $pass   The passphrase (only for RS* algos).
     */
    public function __construct(
        $key = null,
        string $algo = 'HS256',
        int $maxAge = 3600,
        int $leeway = 0,
        string $pass = null
    ) {
        if (!$key)
            $key = env("GATE_JWT_KEY", "O6YyI/TK0EePdIbtiLojqg==");
        $algo = env("GATE_JWT_ALGO", "HS256");
        $maxAge = env("GATE_JWT_MAX_AGE", 3600);
        
        $this->validateConfig($key, $algo, $maxAge, $leeway);

        if (\is_array($key)) {
            $this->registerKeys($key);
            $key = \reset($key); // use first one!
        }

        $this->key        = $key;
        $this->algo       = $algo;
        $this->maxAge     = $maxAge;
        $this->leeway     = $leeway;
        $this->passphrase = $pass;
    }

    /**
     * Register keys for `kid` support.
     *
     * @param array $keys Use format: ['<kid>' => '<key data>', '<kid2>' => '<key data2>']
     *
     * @return self
     */
    public function registerKeys(array $keys): self
    {
        $this->keys = \array_merge($this->keys, $keys);

        return $this;
    }

    /**
     * Encode payload as JWT token.
     *
     * @param array $payload
     * @param array $header  Extra header (if any) to append.
     *
     * @return string URL safe JWT token.
     */
    public function encode(array $payload, array $header = []): string
    {
        $header = ['typ' => 'JWT', 'alg' => $this->algo] + $header;

        $this->validateKid($header);

        if (!isset($payload['iat']) && !isset($payload['exp'])) {
            $payload['exp'] = ($this->timestamp ?: \time()) + $this->maxAge;
        }

        $header    = $this->urlSafeEncode($header);
        $payload   = $this->urlSafeEncode($payload);
        $signature = $this->urlSafeEncode($this->sign($header . '.' . $payload));

        return $header . '.' . $payload . '.' . $signature;
    }

    /**
     * Decode JWT token and return original payload.
     *
     * @param string $token
     * @param bool   $verify
     *
     * @throws JWTException
     *
     * @return array
     */
    public function decode(string $token, bool $verify = true): array
    {
        if (\substr_count($token, '.') < 2) {
            throw new JWTException('Invalid token: Incomplete segments', static::ERROR_TOKEN_INVALID);
        }

        $token = \explode('.', $token, 3);
        if (!$verify) {
            return (array) $this->urlSafeDecode($token[1]);
        }

        $this->validateHeader((array) $this->urlSafeDecode($token[0]));

        // Validate signature.
        if (!$this->checkVerify($token[0] . '.' . $token[1], $token[2])) {
            throw new JWTException('Invalid token: Signature failed', static::ERROR_SIGNATURE_FAILED);
        }

        $payload = (array) $this->urlSafeDecode($token[1]);

        $this->validateTimestamps($payload);

        return $payload;
    }
    /**
     * Verify the signature of given input.
     *
     * @param string $input
     * @param string $signature
     *
     * @throws JWTException When key is invalid.
     *
     * @return bool
     */
    public function verify(string| array $token): bool
    {
        $token = is_array($token) ? $token : \explode('.', $token, 3);
        return $this->checkVerify($token[0] . '.' . $token[1], $token[2]);
    }
    /**
     * Spoof current timestamp for testing.
     *
     * @param int|null $timestamp
     */
    public function setTestTimestamp(int $timestamp = null): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Sign the input with configured key and return the signature.
     *
     * @param string $input
     *
     * @return string
     */
    protected function sign(string $input): string
    {
        // HMAC SHA.
        if (\substr($this->algo, 0, 2) === 'HS') {
            return \hash_hmac($this->algos[$this->algo], $input, $this->key, true);
        }

        $this->validateKey();

        \openssl_sign($input, $signature, $this->key, $this->algos[$this->algo]);

        return $signature;
    }

    /**
     * Verify the signature of given input.
     *
     * @param string $input
     * @param string $signature
     *
     * @throws JWTException When key is invalid.
     *
     * @return bool
     */
    protected function checkVerify(string $input, string $signature): bool
    {
        $algo = $this->algos[$this->algo];

        // HMAC SHA.
        if (\substr($this->algo, 0, 2) === 'HS') {
            return \hash_equals($this->urlSafeEncode(\hash_hmac($algo, $input, $this->key, true)), $signature);
        }

        $this->validateKey();

        $pubKey = \openssl_pkey_get_details($this->key)['key'];

        return \openssl_verify($input, $this->urlSafeDecode($signature, false), $pubKey, $algo) === 1;
    }

    /**
     * URL safe base64 encode.
     *
     * First serialized the payload as json if it is an array.
     *
     * @param array|string $data
     *
     * @throws JWTException When JSON encode fails.
     *
     * @return string
     */
    protected function urlSafeEncode($data): string
    {
        if (\is_array($data)) {
            $data = \json_encode($data, \JSON_UNESCAPED_SLASHES);
            $this->validateLastJson();
        }

        return \rtrim(\strtr(\base64_encode($data), '+/', '-_'), '=');
    }

    /**
     * URL safe base64 decode.
     *
     * @param array|string $data
     * @param bool         $asJson Whether to parse as JSON (defaults to true).
     *
     * @throws JWTException When JSON encode fails.
     *
     * @return array|\stdClass|string
     */
    protected function urlSafeDecode($data, bool $asJson = true)
    {
        if (!$asJson) {
            return \base64_decode(\strtr($data, '-_', '+/'));
        }

        $data = \json_decode(\base64_decode(\strtr($data, '-_', '+/')));
        $this->validateLastJson();

        return $data;
    }

    /**
     * Throw up if input parameters invalid.
     *
     * @codeCoverageIgnore
     */
    protected function validateConfig($key, string $algo, int $maxAge, int $leeway)
    {
        if (empty($key)) {
            throw new JWTException('Signing key cannot be empty', static::ERROR_KEY_EMPTY);
        }

        if (!isset($this->algos[$algo])) {
            throw new JWTException('Unsupported algo ' . $algo, static::ERROR_ALGO_UNSUPPORTED);
        }

        if ($maxAge < 1) {
            throw new JWTException('Invalid maxAge: Should be greater than 0', static::ERROR_INVALID_MAXAGE);
        }

        if ($leeway < 0 || $leeway > 120) {
            throw new JWTException('Invalid leeway: Should be between 0-120', static::ERROR_INVALID_LEEWAY);
        }
    }

    /**
     * Throw up if header invalid.
     */
    protected function validateHeader(array $header)
    {
        if (empty($header['alg'])) {
            throw new JWTException('Invalid token: Missing header algo', static::ERROR_ALGO_MISSING);
        }
        if (empty($this->algos[$header['alg']])) {
            throw new JWTException('Invalid token: Unsupported header algo', static::ERROR_ALGO_UNSUPPORTED);
        }

        $this->validateKid($header);
    }

    /**
     * Throw up if kid exists and invalid.
     */
    protected function validateKid(array $header)
    {
        if (!isset($header['kid'])) {
            return;
        }
        if (empty($this->keys[$header['kid']])) {
            throw new JWTException('Invalid token: Unknown key ID', static::ERROR_KID_UNKNOWN);
        }

        $this->key = $this->keys[$header['kid']];
    }

    /**
     * Throw up if timestamp claims like iat, exp, nbf are invalid.
     */
    protected function validateTimestamps(array $payload)
    {
        $timestamp = $this->timestamp ?: \time();
        $checks    = [
            ['exp', $this->leeway /*          */, static::ERROR_TOKEN_EXPIRED, 'Expired'],
            ['iat', $this->maxAge - $this->leeway, static::ERROR_TOKEN_EXPIRED, 'Expired'],
            ['nbf', -$this->leeway, static::ERROR_TOKEN_NOT_NOW, 'Not now'],
        ];

        foreach ($checks as list($key, $offset, $code, $error)) {
            if (isset($payload[$key])) {
                $offset += $payload[$key];
                $fail    = $key === 'nbf' ? $timestamp <= $offset : $timestamp >= $offset;

                if ($fail) {
                    throw new JWTException('Invalid token: ' . $error, $code);
                }
            }
        }
    }

    /**
     * Throw up if key is not resource or file path to private key.
     */
    protected function validateKey()
    {
        if (\is_string($key = $this->key)) {
            if (\substr($key, 0, 7) !== 'file://') {
                $key = 'file://' . $key;
            }

            $this->key = \openssl_get_privatekey($key, $this->passphrase ?: '');
        }

        if (\PHP_VERSION_ID < 80000 && !\is_resource($this->key)) {
            throw new JWTException('Invalid key: Should be resource of private key', static::ERROR_KEY_INVALID);
        }

        if (\PHP_VERSION_ID > 80000 && !($this->key instanceof \OpenSSLAsymmetricKey
            || $this->key instanceof \OpenSSLCertificate
            || $this->key instanceof \OpenSSLCertificateSigningRequest
        )) {
            throw new JWTException('Invalid key: Should be resource of private key', static::ERROR_KEY_INVALID);
        }
    }

    /**
     * Throw up if last json_encode/decode was a failure.
     */
    protected function validateLastJson()
    {
        if (\JSON_ERROR_NONE === \json_last_error()) {
            return;
        }

        throw new JWTException('JSON failed: ' . \json_last_error_msg(), static::ERROR_JSON_FAILED);
    }
}
