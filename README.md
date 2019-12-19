# Security

Security component for swoft framework.

## Install

- install by composer

```bash
composer require xswoft/security
```

## LICENSE

The Component is open-sourced software licensed under the [Apache license](LICENSE).

## Usage

本组件实现了`BearerToken` 的验证，以及简单的 `ACL`， 使用方法简单.

### BearerToken

在需要权限控制的 `Controller` 添加注解，如下

```
use Xswoft\Security\Middleware\AuthMiddleware;

/**
 * Class AbstractController
 *
 * @Middleware(AuthMiddleware::class)
 */
```

然后在 `config/beans/base.php` 中添加

```php
'auth' => [
    'jwt' => [
       'algorithm' => 'HS256',
       'secret' => '123456'
    ],
],
```

注意 `secret` 不要使用上述值，修改为你自己的值

#### 配置验证管理 AuthManagerInterface

`AuthManager` 是登录验证的核心，本类实现了 `Token` 的验证及缓存，你可以继承这个类实现多种方式登录(配合`accountType`实现)，下面就是一个 `BearerToken` 的 `Demo`

首先实现一个 `Xswoft\Security\Contract\AccountTypeInterface `作为我们登录的通道

```
<?php

namespace App\Model\Logic;

use Swoft\Bean\Annotation\Mapping\Bean;
use Swoft\Bean\Annotation\Mapping\Inject;
use Xswoft\Security\Bean\AuthResult;
use Xswoft\Security\Constants\SecurityConstants;
use Xswoft\Security\Contract\AccountTypeInterface;

/**
 * Class UserLogic
 *
 * @Bean()
 */
class UserLogic implements AccountTypeInterface
{
    /**
     * @Inject()
     *
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * @param array $data
     *
     * @return AuthResult
     * @throws NotFoundException
     */
    public function login($data): AuthResult
    {
        $identity = $data['identity'];
        $credential = $data['credential'];
        $user = $this->dao::findOneByUsername($identity);
        $result = new AuthResult();
        
        if($user instanceof AdminUserBean && $user->verify($credential)){
            $result->setExtendedData([self::ROLE => $user->getIsAdministrator()]);
            $result->setIdentity($user->getId());
        }
        
        return $result;
    }

    /**
     * @param string $identity
     *
     * @return bool
     * @throws \Swoft\Db\Exception\DbException
     */
    public function authenticate(string $identity): bool
    {
        return $this->dao::issetUserById($identity);
    }
}
```

然后在我们自己的 `AuthManagerService` 实现这个登录

```php

use Xswoft\Security\AuthManager;
use Xswoft\Security\Constants\AuthManagerInterface;
use Xswoft\Security\Bean\AuthSession;

/**
 * @Bean()
 */
class AuthManagerService extends AuthManager implements AuthManagerInterface
{
    /**
     * @var string
     */
    protected $cacheClass = Redis::class;

    /**
     * @var bool 开启缓存
     */
    protected $cacheEnable = true;

    public function login(string $identity, string $credential) : AuthSession
    {
        return $this->login(UserLogic::class, [
            'identity' => $identity,
            'credential' => $credential
        ]);
    }
}
```

### ACL

组件中实现了基于 `@Acl` 注解的ACL

我们只需要在需要权限验证的控制器或者方法 添加注解，并且在登录逻辑中设置权限数据，如下

```
use Xswoft\Security\Middleware\AuthMiddleware;

/**
 * Class DemoController
 *
 * @Middleware(AclMiddleware::class)
 * @Acl('resource')
 */
```

本例中只是实现了简单的 `ACL` 检查，当然你完全可以自定义检查机制，只要实现 `Xswoft\Security\Contract\PermissionCheckerInterface`即可
