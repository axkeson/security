<?php declare(strict_types=1);

namespace Xswoft\Security\Bean;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class AuthSession
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class AuthSession
{
    /**
     * User personal information credentials.
     *
     * @var string
     */
    protected $identity = '';

    /**
     * Login method name.
     *
     * @var string
     */
    protected $accountTypeName = '';

    /**
     * Authentication credentials.
     *
     * @var string
     */
    protected $token = '';

    /**
     * Creation time.
     *
     * @var int
     */
    protected $createTime = 0;

    /**
     * expiration time.
     *
     * @var int
     */
    protected $expirationTime = 0;

    /**
     * Expand data, define it yourself.
     *
     * @var array
     */
    protected $extendedData = [];

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     *
     * @return AuthSession
     */
    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return string
     */
    public function getAccountTypeName(): string
    {
        return $this->accountTypeName;
    }

    /**
     * @param string $accountTypeName
     *
     * @return AuthSession
     */
    public function setAccountTypeName(string $accountTypeName): self
    {
        $this->accountTypeName = $accountTypeName;

        return $this;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     *
     * @return AuthSession
     */
    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return int
     */
    public function getCreateTime(): int
    {
        return $this->createTime;
    }

    /**
     * @param int $createTime
     *
     * @return AuthSession
     */
    public function setCreateTime(int $createTime): self
    {
        $this->createTime = $createTime;

        return $this;
    }

    /**
     * @return int
     */
    public function getExpirationTime(): int
    {
        return $this->expirationTime;
    }

    /**
     * @param int $expirationTime
     *
     * @return AuthSession
     */
    public function setExpirationTime(int $expirationTime): self
    {
        $this->expirationTime = $expirationTime;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtendedData(): array
    {
        return (array)$this->extendedData;
    }

    /**
     * @param array $extendedData
     *
     * @return AuthSession
     */
    public function setExtendedData(array $extendedData): self
    {
        $this->extendedData = $extendedData;

        return $this;
    }
}
