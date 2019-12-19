<?php declare(strict_types=1);

namespace Xswoft\Security\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Swoft\Http\Server\Router\Route;
use Xswoft\Security\AclRegister;
use Xswoft\Security\Contract\PermissionCheckerInterface;
use Xswoft\Security\Exception\AccessDeniedException;
use Swoft\Http\Message\Request;
use Swoft\Http\Server\Router\Router;

/**
 * Class AclMiddleware
 *
 * @Bean()
 */
class AclMiddleware
{
    /**
     * @param ServerRequestInterface  $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     * @throws AccessDeniedException
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        /* @var Route $route */
        [$status, , $route] = $request->getAttribute(Request::ROUTER_ATTRIBUTE);
        if ($status !== Router::FOUND) {
            return $handler->handle($request);
        }

        // Controller and method
        $handlerId = $route->getHandler();
        [$className, $method] = explode('@', $handlerId);

        $resource = AclRegister::getAcl($className, $method);
        if (empty($resource)) {
            return $handler->handle($request);
        }
        
        /** @var PermissionCheckerInterface $service */
        $service = BeanFactory::getBean(PermissionCheckerInterface::class);

        if (!$service->check($resource)) {
            throw new AccessDeniedException('No access');
        }

        return $handler->handle($request);
    }
}
