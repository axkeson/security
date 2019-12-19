<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

use Psr\Http\Message\ServerRequestInterface;

interface AuthHandlerInterface
{
    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface;
}
