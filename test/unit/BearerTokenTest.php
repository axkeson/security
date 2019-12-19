<?php declare(strict_types=1);

namespace XswoftTest\Security\Unit;

use Swoft\Bean\BeanFactory;
use Xswoft\Security\Contract\AuthManagerInterface;
use XswoftTest\Security\Testing\MockRequest;
use XswoftTest\Security\Testing\TestManager;

class BearerTokenTest extends AbstractTestCase
{
    public function testHandle()
    {
        /** @var TestManager $manager */
        $manager = BeanFactory::getBean(AuthManagerInterface::class);

        try {
            $session = $manager->testLogin('user', '123456');
            $token = $session->getToken();

            $response = $this->mockServer->request(
                MockRequest::GET,
                '/test/token',
                [],
                ['Authorization' => 'Bearer ' . 'eyJ0eXAiOiJKV1QiLCJhbGciOiJub25lIiwianRpIjoiNGYxZzIzYTEyYWEifQ.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLmNvbSIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUub3JnIiwianRpIjoiNGYxZzIzYTEyYWEiLCJpYXQiOjE1NzQ0MTg1MTIsIm5iZiI6MTU3NDQxODU3MiwiZXhwIjoxNTc0NDIyMTEyLCJ1aWQiOjJ9.']
            );
        } catch (\Exception $e) {
            $response = null;
        }

        vdump($response);
        $response->assertEqualJson(['data' => 'token']);
    }
}
