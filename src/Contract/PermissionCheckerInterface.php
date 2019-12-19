<?php declare(strict_types=1);

namespace Xswoft\Security\Contract;

interface PermissionCheckerInterface
{
    /**
     * @param string $resource
     *
     * @return bool
     */
    public function check(string $resource);
}
