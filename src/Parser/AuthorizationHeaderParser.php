<?php declare(strict_types=1);

namespace Xswoft\Security\Parser;

use Psr\Http\Message\ServerRequestInterface;
use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\BeanFactory;
use Xswoft\Security\Constants\SecurityConstants;
use Xswoft\Security\Contract\AuthHandlerInterface;
use Xswoft\Security\Contract\AuthorizationParserInterface;
use Xswoft\Security\Exception\AuthException;
use Xswoft\Security\Parser\Handler\BearerTokenHandler;

/**
 * Class AuthorizationHeaderParser
 *
 * @Bean()
 */
class AuthorizationHeaderParser implements AuthorizationParserInterface
{
    /**
     * The parsers
     *
     * @var array
     */
    private $authTypes = [
        BearerTokenHandler::NAME => BearerTokenHandler::class
    ];

    private $headerKey = SecurityConstants::HEADER_KEY;

    /**
     * @param ServerRequestInterface $request
     *
     * @return ServerRequestInterface
     * @throws AuthException
     */
    public function parse(ServerRequestInterface $request): ServerRequestInterface
    {
        $authValue = $request->getHeaderLine($this->headerKey);

        if (empty($authValue)) {
            throw new AuthException("Header 'Authorization' is required");
        }

        $type = $this->getHeadString($authValue);

        if (empty($type) || !isset($this->authTypes[$type])) {
            throw new AuthException("Header 'Authorization' is required");
        }

        $handler = BeanFactory::getBean($this->authTypes[$type]);
        if (! $handler instanceof AuthHandlerInterface) {
            throw new AuthException(sprintf('%s  should implement Swoft\Auth\Mapping\AuthHandlerInterface'));
        }
        $request = $handler->handle($request);

        return $request;
    }

    /**
     * @param string $value
     *
     * @return string
     */
    private function getHeadString(string $value): string
    {
        return explode(' ', $value)[0] ?? '';
    }
}
