<?php

return [
  "without" => [
    '/',
    'logout'
  ],
  "allow" => [
    "login",
    "logout",
    "admin.dashboard"
  ],
  'guard' => 'admin',
  "guest_redirect" => 'login',
  "basePrefix" => 'admin'
];
