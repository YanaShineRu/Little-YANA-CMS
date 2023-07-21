<?php
$hashedPassword = password_hash('admin', PASSWORD_DEFAULT);

return [
    'username' => 'admin',
    'password' => $hashedPassword
];
?>