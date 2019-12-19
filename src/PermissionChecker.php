<?php declare(strict_types=1);

namespace Xswoft\Security;

use Swoft\Bean\Annotation\Mapping\Bean;
use Xswoft\Security\Bean\AuthSession;
use Xswoft\Security\Constants\SecurityConstants;
use Xswoft\Security\Contract\PermissionCheckerInterface;

/**
 * Class PermissionChecker
 *
 * @Bean()
 */
class PermissionChecker implements PermissionCheckerInterface
{
    /**
     * @param string $resource
     *
     * @return bool
     */
    public function check(string $resource)
    {
        if (empty($this->getPermission())) {
            return false;
        }

        if (in_array($resource, $this->getPermission())) {
            return true;
        }

        return false;
    }

    /**
     * @return array
     */
    protected function getPermission(): array
    {
        if (! $this->getSession()) {
            return [];
        }

        $permissions = $this->getSession()->getExtendedData()[SecurityConstants::AUTH_PERMISSION] ?? [];

        if (is_string($permissions)) {
            return explode(',', $permissions);
        } else {
            return $permissions;
        }
    }

    /**
     * @return AuthSession
     */
    protected function getSession()
    {
        return context()->get(SecurityConstants::AUTH_SESSION);
    }
}
