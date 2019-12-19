<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

use Psr\Http\Message\ServerRequestInterface;

interface AuthorizationParserInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface;
}
