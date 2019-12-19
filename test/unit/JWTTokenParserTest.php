<?php declare(strict_types=1);

namespace XswoftTest\Security\Unit;

use Xswoft\Security\Bean\AuthSession;
use Xswoft\Security\Contract\TokenParserInterface;
use XswoftTest\Security\Testing\TestAccount;

class JWTTokenParserTest extends AbstractTestCase
{
    public function testGetToken()
    {
        /** @var TokenParserInterface $parser */
        $parser = \Swoft::getBean(TokenParserInterface::class);
        $session = new AuthSession();
        $session->setIdentity('1');
        $session->setExpirationTime(time()+10);
        $session->setAccountTypeName(TestAccount::class);
        $token = $parser->getToken($session);
        $this->assertStringStartsWith('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', $token);

        return $token;
    }

    public function testGetSession()
    {
        $token = $this->testGetToken();

        /** @var TokenParserInterface $parser */
        $parser = \Swoft::getBean(TokenParserInterface::class);
        /** @var AuthSession $session */
        $session = $parser->getSession($token);

        $this->assertEquals(1, $session->getIdentity());
    }
}
