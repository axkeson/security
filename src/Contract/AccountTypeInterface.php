<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

use Xswoft\Security\Bean\AuthResult;

interface AccountTypeInterface
{
    const LOGIN_IDENTITY = 'identity';

    const LOGIN_CREDENTIAL = 'credential';

    /**
     * @param array $data
     *
     * @return AuthResult
     */
    public function login(array $data): AuthResult;

    /**
     * @param string $identity
     *
     * @return bool
     */
    public function authenticate(string $identity): bool;
}
