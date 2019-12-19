<?php declare(strict_types=1);

namespace XswoftTest\Security\Testing;

use Swoft\Bean\Annotation\Mapping\Bean;
use Xswoft\Security\Bean\AuthResult;
use Xswoft\Security\Contract\AccountTypeInterface;

/**
 * Class TestAccount
 *
 * @Bean()
 */
class TestAccount implements AccountTypeInterface
{
    /**
     * @param array $data
     *
     * @return AuthResult
     */
    public function login(array $data): AuthResult
    {
        $name = $data[0] ?? '';
        $pw = $data[1] ?? '';
        $result = new AuthResult();
        if ($name !== '' && $pw !== '') {
            $result->setIdentity('1');
            $result->setExtendedData(['permission'=>'qaz,wsx']);
        } else {
            $result->setIdentity('1');
        }

        return $result;
    }

    /**
     * Authentication successful.
     *
     * @param string $identity
     *
     * @return bool
     */
    public function authenticate(string $identity): bool
    {
        return true;
    }
}
