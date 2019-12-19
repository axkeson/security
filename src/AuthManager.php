<?php declare(strict_types=1);

namespace Xswoft\Security;

use RuntimeException;
use Psr\SimpleCache\CacheInterface;
use Psr\SimpleCache\InvalidArgumentException;
use Swoft\Bean\BeanFactory;
use Xswoft\Security\Bean\AuthResult;
use Xswoft\Security\Bean\AuthSession;
use Xswoft\Security\Constants\SecurityConstants;
use Xswoft\Security\Contract\AccountTypeInterface;
use Xswoft\Security\Contract\AuthManagerInterface;
use Xswoft\Security\Contract\TokenParserInterface;
use Xswoft\Security\Exception\AuthException;
use Xswoft\Security\Parser\JWTTokenParser;

class AuthManager implements AuthManagerInterface
{
    /**
     * @var string
     */
    protected $prefix = 'xswoft.token.';

    /**
     * @var int
     */
    protected $sessionDuration = 86400;

    /**
     * @var string
     */
    protected $tokenParserClass = JWTTokenParser::class;

    /**
     * @var TokenParserInterface
     */
    private $tokenParser;

    /**
     * @var bool
     */
    protected $cacheEnable = false;

    /**
     * @var CacheInterface
     */
    private $cache;

    /**
     * @var string
     */
    protected $cacheClass = '';

    /**
     * @inheritdoc
     */
    public function login(string $accountTypeName, array $data): AuthSession
    {
        if (! $account = $this->getAccountType($accountTypeName)) {
            throw new AuthException('Invalid Auth Account Type');
        }

        $result = $account->login($data);
        if (! $result instanceof AuthResult || $result->getIdentity() === '') {
            throw new AuthException('Auth Login Falied');
        }

        $session = $this->generateSession($accountTypeName, $result);
        if ($this->cacheEnable === true) {
            try {
                $this->getCacheClient()
                    ->set($this->getCacheKey($session->getIdentity(), $session->getExtendedData()), $session->getToken(), $this->sessionDuration);
            } catch (InvalidArgumentException $e) {
                throw new AuthException(
                    sprintf('%s Invalid Argument : %s', $session->getIdentity(), $e->getMessage())
                );
            }
        }

        return $session;
    }

    /**
     * @inheritdoc
     */
    public function authenticateToken(string $token): bool
    {
        try {
            /** @var AuthSession $session */
            $session = $this->getTokenParser()->getSession($token);
        } catch (\Exception $e) {
            throw new AuthException($e->getMessage());
        }

        if (! $session) {
            return false;
        }

        if ($session->getExpirationTime() < time()) {
            throw new AuthException('Auth Session Expired');
        }

        if (! $account = $this->getAccountType($session->getAccountTypeName())) {
            throw new AuthException('Auth Session Expired');
        }

        if (! $account->authenticate($session->getIdentity())) {
            throw new AuthException('Auth Session Expired');
        }

        $this->setSession($session);

        return true;
    }

    /**
     * @param AuthSession $session
     */
    private function setSession(AuthSession $session)
    {
        context()->set(SecurityConstants::AUTH_SESSION, $session);
    }

    /**
     * @param string     $accountTypeName
     * @param AuthResult $authResult
     *
     * @return AuthSession
     */
    private function generateSession(string $accountTypeName, AuthResult $authResult)
    {
        $startTime = time();
        $exp = $startTime + (int)$this->sessionDuration;
        $session = new AuthSession();
        $session->setExtendedData($authResult->getExtendedData())
            ->setExpirationTime($exp)
            ->setCreateTime($startTime)
            ->setIdentity($authResult->getIdentity())
            ->setAccountTypeName($accountTypeName);

        $session->setToken($this->getTokenParser()->getToken($session));
        
        return $session;
    }

    /**
     * @param string $name
     *
     * @return AccountTypeInterface|null
     */
    private function getAccountType(string $name)
    {
        if (! BeanFactory::hasBean($name)) {
            return null;
        }

        $account = BeanFactory::getBean($name);

        if (! $account instanceof AccountTypeInterface) {
            return null;
        }

        return $account;
    }

    /**
     * @throws RuntimeException When TokenParser missing or error.
     */
    private function getTokenParser(): TokenParserInterface
    {
        if (! $this->tokenParser instanceof TokenParserInterface) {
            if (! BeanFactory::hasBean($this->tokenParserClass)) {
                throw new \RuntimeException('Can`t find tokenParserClass');
            }
            $tokenParser = BeanFactory::getBean($this->tokenParserClass);
            if (! $tokenParser instanceof TokenParserInterface) {
                throw new \RuntimeException("TokenParser need implements Swoft\Auth\Mapping\TokenParserInterface ");
            }
            $this->tokenParser = $tokenParser;
        }

        return $this->tokenParser;
    }

    /**
     * @return CacheInterface
     */
    private function getCacheClient(): CacheInterface
    {
        if (! BeanFactory::hasBean($this->cacheClass)) {
            return null;
        }

        $cache = BeanFactory::getBean($this->cacheClass);

        if (! $cache instanceof CacheInterface) {
            throw new \RuntimeException('CacheClient need implements Psr\SimpleCache\CacheInterface');
        }
        $this->cache = $cache;

        return $this->cache;
    }

    /**
     * @param string $identity
     * @param array  $extendedData
     *
     * @return string
     */
    private function getCacheKey(string $identity, array $extendedData): string
    {
        if (empty($extendedData)) {
            return $this->prefix . $identity;
        }
        $str = json_encode($extendedData);

        return $this->prefix . $identity . '.' . md5($str);
    }
}
