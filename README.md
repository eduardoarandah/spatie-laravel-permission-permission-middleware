# Better control your permissions using namespaces

![checking2](https://user-images.githubusercontent.com/4065733/29992872-cd0e0838-8f6b-11e7-8c62-ddfcf3764cb1.png)
Example of execution with debugbar

One common problem is defining permissions. 

- If you are very specific, you end up with lots of permissions ('can-edit-users-in-admin')
- If you are not specific, you can't deny access to specific areas ('admin-access')

With a **namespacing approach**, your application permissions will always stay the same, even though the company changes structure 

# Requirements
- [spatie/laravel-permission](https://github.com/spatie/laravel-permission)

# Installation
- copy PermissionMiddleware.php to app/Http/Middleware/
- add this line to app/Http/Kernel.php

```
protected $routeMiddleware = [
    ...

    'permission'    => \App\Http\Middleware\PermissionMiddleware::class,
];
```

# How to use

- When using this middleware, write abilities as specific as possible, separating by a **dot**

## example in routes/web.php

```
route::get('admin/auth/users/create', 'UsersController@create')
->middleware('permission:admin.auth.user.modify.create');

```
- Dots make your permissions **hierarchical** 

Permission is this: **admin.auth.user.modify.create**

if any of this permissions is found, access will be granted:

- admin
- admin.auth
- admin.auth.user
- admin.auth.user.modify
- admin.auth.user.modify.create

So, if you have a user with a permission 'admin.auth' he will have access

My suggestion is namespacing by read-modify and then by CRUD

- **user** full control
- **user.read** can read
- **user.modify** can create, update and delete
- **user.modify.create** can create
- **user.modify.update** can update
- **user.modify.delete** can delete

# How to debug

Use debugbar and click in "gate"

[https://github.com/barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)

