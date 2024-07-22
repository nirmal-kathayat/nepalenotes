<?php

return [
  "without" => [
    '/',
    'logout'
  ],
  "allow" => [
    "login",
    "logout",
    "admin.dashboard",
    "admin.permission",
    "admin.permission.create",
    "admin.permission.store",
    "admin.permission.edit",
    "admin.permission.update",
    "admin.permission.delete",
    "admin.role",
    "admin.role.create",
    "admin.role.store",
    "admin.role.edit",
    "admin.role.update",
    "admin.role.delete",
    "admin.user",
    "admin.user.create",
    "admin.user.store",
    "admin.user.edit",
    "admin.user.update",
    "admin.user.delete",
  ],
  'guard' => 'admin',
  "guest_redirect" => 'login',
  "basePrefix" => 'admin'
];
