<?php declare(strict_types=1);

namespace Xswoft\Security;

/**
 * Class AclRegister
 *
 * @since 2.0
 */
class AclRegister
{
    /**
     * All Acls within controller and action
     *
     * @var array
     *
     * @example
     * [
     *     'className' => [
     *          'controller' => 'aclName'
     *          'actionName' => 'aclName'
     *     ]
     * ]
     */
    private static $acls = [];

    /**
     * Register controller acl.
     *
     * @param string $name      middleware name
     * @param string $className class name
     *
     * @return void
     */
    public static function registerByClassName(string $name, string $className): void
    {
        self::$acls[$className]['controller'] = $name;
    }

    /**
     * Register action acl.
     *
     * @param string $name
     * @param string $className
     * @param string $methodName
     *
     * @return void
     */
    public static function registerByMethodName(string $name, string $className, string $methodName): void
    {
        self::$acls[$className][$methodName] = $name;
    }

    /**
     * @param string $className
     * @param string $methodName
     *
     * @return string
     */
    public static function getAcl(string $className, string $methodName): string
    {
        $acl = self::$acls[$className][$methodName] ?? '';
        if (!empty($acl)) {
            return $acl;
        }

        return self::$acls[$className]['controller'] ?? '';
    }
}