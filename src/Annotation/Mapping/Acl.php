<?php declare(strict_types=1);

namespace Xswoft\Security\Annotation\Mapping;

use Doctrine\Common\Annotations\Annotation\Attribute;
use Doctrine\Common\Annotations\Annotation\Attributes;
use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Acl
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 * @Attributes({
 *     @Attribute("resource", type="string"),
 * })
 *
 * @since 2.0
 */
final class Acl
{
    /**
     * Acl resource.
     *
     * @Required()
     *
     * @var string
     */
    private $resource = '';

    /**
     * Controller constructor.
     *
     * @param array $values
     */
    public function __construct(array $values)
    {
        if (isset($values['value'])) {
            $this->resource = $values['value'];
        }
        if (isset($values['resource'])) {
            $this->resource = $values['resource'];
        }
    }

    /**
     * @return string
     */
    public function getResource(): string
    {
        return $this->resource;
    }
}
