<?php declare(strict_types=1);

namespace Xswoft\Security\Parser;

use Firebase\JWT\JWT;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Config\Annotation\Mapping\Config;
use Xswoft\Security\Bean\AuthSession;
use Xswoft\Security\Contract\TokenParserInterface;

/**
 * Class JWTTokenParser
 *
 * @Bean()
 */
class JWTTokenParser implements TokenParserInterface
{
    const ALGORITHM_HS256 = 'HS256';

    const ALGORITHM_HS512 = 'HS512';

    const ALGORITHM_HS384 = 'HS384';

    const ALGORITHM_RS256 = 'RS256';

    /**
     * @Config("auth.jwt.algorithm")
     * @var string
     */
    protected $algorithm = self::ALGORITHM_HS256;

    /**
     * @Config("auth.jwt.secret")
     * @var string
     */
    protected $secret = 'xswoft';

    /**
     * @param AuthSession $session
     *
     * @return string
     */
    public function getToken(AuthSession $session): string
    {
        $tokenData = $this->create(
            $session->getAccountTypeName(),
            $session->getIdentity(),
            $session->getCreateTime(),
            $session->getExpirationTime(),
            $session->getExtendedData()
        );

        return $this->encode($tokenData);
    }

    /**
     * @param string $token
     *
     * @return AuthSession
     */
    public function getSession(string $token): AuthSession
    {
        $tokenData = $this->decode($token);

        return (new AuthSession())->setAccountTypeName($tokenData->iss)
            ->setIdentity($tokenData->sub)
            ->setCreateTime($tokenData->iat)
            ->setExpirationTime($tokenData->exp)
            ->setToken($token)
            ->setExtendedData((array)$tokenData->data)
            ;
    }

    /**
     * @param string $issuer
     * @param string $user
     * @param int    $iat
     * @param int    $exp
     * @param array  $data
     *
     * @return array
     */
    private function create(string $issuer, string $user, int $iat, int $exp, array $data): array
    {
        return [
            'iss'  => $issuer,
            'sub'  => $user,
            'iat'  => $iat,
            'exp'  => $exp,
            'data' => $data,
        ];
    }

    /**
     * @param mixed $token
     *
     * @return string
     */
    private function encode($token): string
    {
        return (string)JWT::encode($token, $this->secret, $this->algorithm);
    }

    /**
     * @param string $token
     *
     * @return mixed
     */
    private function decode(string  $token)
    {
        return JWT::decode($token, $this->secret, [$this->algorithm]);
    }
}
