### /config/config.php

```php
<?php
// DB 설정
define('DB_HOST', 'mysql-db');
define('DB_USER', 'vuln1');
define('DB_PASS', 'vuln1');
define('DB_NAME', 'vuln1');

// 세션 보안 설정
ini_set('session.cookie_httponly', 1);
ini_set('session.use_strict_mode', 1);

// 세션 시작 (안전하게)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

```