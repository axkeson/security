<?php declare(strict_types=1);

namespace XswoftTest\Security\Testing\Controller;

use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Xswoft\Security\Annotation\Mapping\Acl;

/**
 * Class RouteController
 *
 * @Controller("test")
 */
class RouteController extends BaseController
{
    /**
     * @RequestMapping("token")
     *
     * @return string
     */
    public function token(): string
    {
        return 'token';
    }

    /**
     * @RequestMapping("allow")
     * @Acl("qaz")
     * @return string
     */
    public function allow(): string
    {
        return 'allow';
    }

    /**
     * @RequestMapping("notallow")
     * @Acl("abc")
     * @return string
     */
    public function notallow(): string
    {
        return 'notallow';
    }
}
