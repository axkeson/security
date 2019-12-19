<?php declare(strict_types=1);

namespace XswoftTest\Security\Unit;

use Swoft\Bean\BeanFactory;
use Xswoft\Security\Contract\AuthManagerInterface;
use XswoftTest\Security\Testing\MockRequest;
use XswoftTest\Security\Testing\TestManager;

class AclTest extends AbstractTestCase
{
    public function testAllow()
    {
        /** @var TestManager $manager */
        $manager = BeanFactory::getBean(AuthManagerInterface::class);

        try {
            $session = $manager->testLogin('user', '123456');
            $token = $session->getToken();

            $response = $this->mockServer->request(
                MockRequest::GET,
                '/test/allow',
                [],
                ['Authorization' => 'Bearer ' . $token]
            );
        } catch (\Exception $e) {
            $response = null;
        }

        $response->assertEqualJson(['data' => 'allow']);
    }

    public function testNotAllow()
    {
        /** @var TestManager $manager */
        $manager = BeanFactory::getBean(AuthManagerInterface::class);

        try {
            $session = $manager->testLogin('user', '123456');
            $token = $session->getToken();

            $response = $this->mockServer->request(
                MockRequest::GET,
                '/test/notallow',
                [],
                ['Authorization' => 'Bearer ' . $token]
            );
        } catch (\Exception $e) {
            $response = null;
        }

        $response->assertEqualJson(['data' => 'notallow']);
    }
}
