Aclkit
=======

PHP library that facilitates authorizing users to access specific content in a web application. Aclkit 
provide two components and a wrapper class.

* RoleCheck - deals with system role structures that use role inheritance
* PathAuthorizer - resolves paths or URLs to a governing role
* Acl - wrapper interface for dealing with PathAuthorizer and RoleCheck as a single system


Not Provided
-------------

The scope of Aclkit is checking authorization, not dealing with users. Your client system must provide 
user services including login, persistance, and assigning roles to users. Aclkit provides two contracts 
(interfaces) so that your system's users are compatible with Aclkit.


### Contracts\UserWithRoles

The UserWithRoles contract requires one method.

    public function getRoles();
    
Your systems user objects should implement this interface. The method `getRoles` should return an array
list of all the roles the user has been directly granted in the system.


### Contracts\UserProvider

The UserProvider contract requires one method.

    public function getUser($user);
    
Your system can provide an object that implements UserProvider which will locate "users" or objects 
implementing the UserWithRoles interface.

This system allows your code to call Aclkit methods with mixed input of your choosing. For example 
you might want to do authorization checks using input like...

* User's database ID
* User's login name
* User's email address

To support this your implementation of UserProvider should check whether the `$user` argument to 
`getUser()` is an expected identifier and then return the identified user object.

Additionally the `getUser()` method can return the "currently logged in user" when the `$user` 
argument is null. This will allow your system to call Aclkit methods with no user specified and 
have authorization checks run against the currently logged in user.


RoleCheck
----------

The RoleCheck class deals with a role inheritance tree. Inheritance can be a single linear inheritance 
or a complex branching system. How you organize those roles is up to you. 

One possible use is a series of action based roles (e.g. 'can-delete-posts', 'can-add-users') with 
domain based role names (e.g. 'sales', 'front-office') that inherit specific actions. If you build 
your application to only check authorization against the action based roles you are provided with  
flexibility when permission requirements change.

RoleCheck constructor requires a role configuration array and an optional `UserProvider` object. There 
is an example of the configuration array here and in the source code config-roles-example.php.

    [
        // Roles based on specific actions
        'view-catalog' => [],
        'edit-catalog' => ['view-catalog'],
        'view-sales' => [],
        'ok-returns' => [],
        'enter-sales' => [],
        'delete-sales' => [],
        'view-reports' => [],
        'make-payments' => [],
        'manage-users' => [],
    
        // Roles based on domain responsibilities
        'guest' => ['view-catalog'],
        'user' => ['guest'],
        'sales' => ['user', 'edit-catalog', 'enter-sales'],
        'sales-manager' => ['sales', 'delete-sales'],
        'service' => ['user', 'view-sales', 'ok-returns'],
        'fiscal' => ['sales', 'view-sales', 'make-payments'],
        'audit' => ['fiscal'],
        'admin' => ['audit', 'manage-users'],
        'super' => ['*'],
    ]

In this configuration array the roles that the system recognizes are the array indexes. The array 
values for each index indicate what other roles the index role will inherit. In this example users 
with the role 'service' also inherit the role 'user' and via 'user' inherit 'guest' and continuing 
the inheritance 'view-catalog'. Users who directly have the service role also get the action based 
roles for 'view-sales' and 'ok-returns'.

_Effective permissions for users with direct role 'service'_

* service
* user
* view-sales
* ok-returns
* guest
* view-catalog

> All valid roles must be declared as index items in the configuration array, before they can be
> inherited. If a role appears only in the inherited (right-side value) array, it will be ignored.

Once RoleCheck is instantiated with its configuration it can be used to check whether a user has a role
(directly or through inheritance).

    $roleCheck = new RoleCheck($config);
    $authorized = $roleCheck->hasRole('make-payments', $user);
    
If RoleCheck is given access to your system's `UserProvider` the second argument becomes as flexible as 
your user `UserProvider` implementation.

    $userProvider = new MySystemUserLocator();
    $roleCheck = new RoleCheck($config, $userProvider);
    $authorized = $roleCheck->hasRole('view-sales', 'mary@users.com');
    $authorized = $roleCheck->hasRole('ok-returns');

To protect against configuration errors `RoleCheck` will throw a `RoleNotDefined` exception if a role is
tested that is not defined in the configuration.


PathAuthorizer
---------------

PathAuthorizer provides a system for mapping paths or URLs to required roles.

PathAuthorizer constructor needs a configuration array and optionally a list of base paths (path 
prefixes that should be ignored during matching, more about this below). There is an example of the 
configuration array here and in the project file config-paths-example.php.

    [
        'catalog' => 'view-catalog',
        'catalog/edit' => 'edit-catalog',
        'sales' => 'view-sales',
        'sales/rma' => 'ok-returns',
        'sales/entry' => 'enter-sales',
        'sales/admin' => 'delete-sales',
        'reports' => 'view-reports',
        'fiscal' => 'fiscal',
        'fiscal/payments' => 'make-payments',
        'settings' => 'admin',
        'settings/users' => 'manage-users',
    ]

The configuration lists a path or URL portion as the array index and the value holds the system role 
required to access that path.

When testing path input PathAuthorizer does the following.

* Strip out http(s) protocol and host if that exists in the input
* Strip out any base paths provided to the contructor
* Find the most specific path rule that matches processed path

In this example instantiate using the the example array above as `$config`.
    
    $pathAuthorizer = new PathAuthorizer($config);
    
Now you can determine the required role based on a request relative path.

    $requiredRole = $pathAuthorizer->requiredRole('/fiscal/payments/123');
    // $requiredRole = 'make-payments'
    
Or based on the full request URL.

    $requiredRole = $pathAuthorizer->requiredRole('https://www.xyz.com/sales/entry/2017/new');
    // $requiredRole = 'enter-sales'

PathAuthorizer also provides option to simply return the matched rule string.

    $pathAuthorizer = new PathAuthorizer($config);
    $rule = $pathAuthorizer->matchedRule('https://www.xyz.com/sales/entry/2017/new');
    // $rule = 'sales/entry'


### Default Required Role

If your PathAuthorizer configuration includes a rule with empty string `""` index that role will be 
returned for all paths that do not match a more specific rule. This allows you to configure a default 
minimum required role for the entire system.

    [
        '' => 'guest',
        'catalog' => 'view-catalog',
        'catalog/edit' => 'edit-catalog',
        ...
    ]


### Base Paths

Base paths is an optional second argument to PathAuthorizer and allows your system to specify paths 
that will appear at the beginning of URLs that are not relevant to deciding required roles. If (after
removing protocol and host) a base path string is found at the beginning of the path argument it 
will be stripped off before matching rules.

    $pathAuthorizer = new PathAuthorizer($config, ['foo']);
    $rule = $pathAuthorizer->matchedRule('https://www.xyz.com/foo/catalog/edit/123');
    // $rule = 'catalog/edit'
    $requiredRole = $pathAuthorizer->requiredRole('https://www.xyz.com/foo/catalog/edit/123');
    // $requiredRole = 'edit-catalog'


Acl
----

Acl is an aggregate class that simplifies the interface when using RoleCheck and PathAuthorizer 
together. 

Acl is instantiated with the combined arguments for both components.

    $userProvider = new MySystemUserLocator();
    $optionalBasePaths = [ 'foo' ];
    $acl = new Acl($roleConfig, $pathRules, $userProvider, $optionalBasePaths);
    
Acl exposes the main methods of both components.

    $authorized = $acl->hasRole('ok-returns', $user);
    $authorized = $acl->allowedPath('https://www.xyz.com/foo/catalog/edit/123', $user);
    
If your `UserProvider` system provides a default (currently logged in) user the second argument
becomes optional.

    $authorized = $acl->hasRole('ok-returns');
    $authorized = $acl->allowedPath('https://www.xyz.com/foo/catalog/edit/123');
