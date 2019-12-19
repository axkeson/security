<?php declare(strict_types=1);

namespace XswoftTest\Security\Testing;

use Xswoft\Security\AuthManager;

class TestManager extends AuthManager
{
    protected $cacheClass = \Redis::class;

    protected $cacheEnable = false;

    /**
     * @param string $username
     * @param string $password
     *
     * @return \Xswoft\Security\Bean\AuthSession
     * @throws \Xswoft\Security\Exception\AuthException
     */
    public function testLogin(string $username, string $password)
    {
        return $this->login(TestAccount::class, [
            $username,
            $password
        ]);
    }
}
