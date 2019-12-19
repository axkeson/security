<?php declare(strict_types=1);

namespace XswoftTest\Security\Testing\Controller;

use Swoft\Http\Server\Annotation\Mapping\RequestMapping;

/**
 * Class BaseController
 *
 * @since 2.0
 */
abstract class BaseController
{
    /**
     * @RequestMapping()
     *
     * @return array
     */
    public function baseMethod(): array
    {
        return ['baseMethod'];
    }
}