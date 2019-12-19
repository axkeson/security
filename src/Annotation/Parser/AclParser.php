<?php declare(strict_types=1);

namespace Xswoft\Security\Annotation\Parser;

use Swoft\Annotation\Annotation\Mapping\AnnotationParser;
use Swoft\Annotation\Annotation\Parser\Parser;
use Xswoft\Security\AclRegister;
use Xswoft\Security\Annotation\Mapping\Acl;

/**
 * Class AclParser
 *
 * @AnnotationParser(Acl::class)
 *
 * @since 2.0
 */
class AclParser extends Parser
{
    /**
     * Parse middleware
     *
     * @param int $type
     * @param Acl $annotationObject
     *
     * @return array
     */
    public function parse(int $type, $annotationObject): array
    {
        $resource = $annotationObject->getResource();

        if ($type === self::TYPE_CLASS) {
            AclRegister::registerByClassName($resource, $this->className);
            return [];
        }

        if ($type === self::TYPE_METHOD) {
            AclRegister::registerByMethodName($resource, $this->className, $this->methodName);
            return [];
        }

        return [];
    }
}
