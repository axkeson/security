<?php

namespace Xswoft\Security\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Xswoft\Security\Contract\AuthorizationParserInterface;
use Xswoft\Security\Exception\AuthException;

/**
 * Class AuthMiddleware
 *
 * @Bean()
 */
class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws AuthException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $parser = BeanFactory::getBean(AuthorizationParserInterface::class);
        if (!$parser instanceof AuthorizationParserInterface) {
            throw new AuthException('AuthorizationParser should implement Swoft\Auth\Mapping\AuthorizationParserInterface');
        }

        $request = $parser->parse($request);
        $response = $handler->handle($request);

        return $response;
    }
}
