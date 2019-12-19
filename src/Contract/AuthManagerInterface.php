<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

use Xswoft\Security\Bean\AuthSession;
use Xswoft\Security\Exception\AuthException;

interface AuthManagerInterface
{
    /**
     * @param string $accountTypeName
     * @param array  $data
     *
     * @return AuthSession
     * @throws AuthException
     */
    public function login(string $accountTypeName, array $data): AuthSession;

    /**
     * @param string $token
     *
     * @return bool
     * @throws AuthException
     */
    public function authenticateToken(string $token): bool;
}
