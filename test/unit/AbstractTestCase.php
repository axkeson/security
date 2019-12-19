<?php declare(strict_types=1);

namespace XswoftTest\Security\Unit;

use PHPUnit\Framework\TestCase;
use Swoft\Bean\BeanFactory;
use XswoftTest\Security\Testing\MockHttpServer;

class AbstractTestCase extends TestCase
{
    /**
     * @var MockHttpServer
     */
    protected $mockServer;

    public function setUp()
    {
        $this->mockServer = BeanFactory::getBean(MockHttpServer::class);
    }

    /**
     * TODO:
     * - 登录获取TOken
     * - Token 解析验证（成功、失败 header 错误 过期）
     * - Acl 验证 （空、没有、未登录、自定义服务）
     * - JWT Token
     */
}
