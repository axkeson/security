<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

use Xswoft\Security\Bean\AuthSession;

interface TokenParserInterface
{
    /**
     * @param AuthSession $session
     *
     * @return string
     */
    public function getToken(AuthSession $session):string ;

    /**
     * @param string $token
     *
     * @return AuthSession
     */
    public function getSession(string $token):AuthSession ;
}
