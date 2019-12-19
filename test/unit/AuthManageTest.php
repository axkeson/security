<?php declare(strict_types=1);

namespace XswoftTest\Security\Unit;

use Swoft\Bean\BeanFactory;
use Xswoft\Security\Contract\AuthManagerInterface;
use XswoftTest\Security\Testing\TestManager;

class AuthManageTest extends AbstractTestCase
{
    public function testHandle()
    {
        /** @var TestManager $manager */
        $manager = BeanFactory::getBean(AuthManagerInterface::class);

        try {
            $session = $manager->testLogin('user', '123456');
            $token = $session->getToken();
        } catch (\Exception $e) {
            $token = null;
        }

        $this->assertStringStartsWith('eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9', $token);
    }
}
