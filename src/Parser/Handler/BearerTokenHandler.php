<?php declare(strict_types=1);

namespace Xswoft\Security\Parser\Handler;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Xswoft\Security\Constants\SecurityConstants;
use Xswoft\Security\Contract\AuthHandlerInterface;
use Xswoft\Security\Contract\AuthManagerInterface;
use Xswoft\Security\Exception\AuthException;

/**
 * Class BearerTokenHandler
 *
 * @Bean()
 */
class BearerTokenHandler implements AuthHandlerInterface
{
    const NAME = 'Bearer';

    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     * @throws AuthException
     */
    public function handle(ServerRequestInterface $request): ServerRequestInterface
    {
        $token = $this->getToken($request);

        /** @var AuthManagerInterface $manager */
        $manager = BeanFactory::getBean(AuthManagerInterface::class);

        if ($token) {
            $res = $manager->authenticateToken($token);
            $request = $request->withAttribute(SecurityConstants::IS_LOGIN, $res);
        }

        return $request;
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return null|string|string[]
     */
    private function getToken(ServerRequestInterface $request)
    {
        $authHeader = $request->getHeaderLine(SecurityConstants::HEADER_KEY) ?? '';
        $authQuery = $request->getQueryParams()['token'] ?? '';

        return $authQuery ?: $this->parseValue($authHeader);
    }

    /**
     * @param string $string
     *
     * @return null|string|string[]
     */
    private function parseValue(string $string)
    {
        if (strpos(trim($string), self::NAME) !== 0) {
            return null;
        }
        return preg_replace('/.*\s/', '', $string);
    }
}
