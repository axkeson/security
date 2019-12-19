<?php declare(strict_types=1);

namespace Xswoft\Security\Bean;

use Swoft\Bean\Annotation\Mapping\Bean;

/**
 * Class AuthResult
 *
 * @Bean(scope=Bean::PROTOTYPE)
 */
class AuthResult
{
    /**
     * @var string
     */
    protected $identity = '';

    /**
     * @var string
     */
    protected $permission = '';

    /**
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
     * @return AuthResult
     */
    public function setIdentity(string $identity): self
    {
        $this->identity = $identity;

        return $this;
    }

    /**
     * @return string
     */
    public function getPermission(): string
    {
        return $this->permission;
    }

    /**
     * @param string $permission
     *
     * @return static
     */
    public function setPermission(string $permission): self
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * @return array
     */
    public function getExtendedData(): array
    {
        return $this->extendedData;
    }

    /**
     * @param array $extendedData
     *
     * @return AuthResult
     */
    public function setExtendedData(array $extendedData): self
    {
        $this->extendedData = $extendedData;

        return $this;
    }
}
