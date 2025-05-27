🔎 취약점 개선 사례 + 페이로드 비교

## SQL Injection

`vuln0`:
```php
SELECT * FROM users WHERE username = '$username' AND password = '$password'
```

- 페이로드: ' OR 1=1 --

`vuln1`:
```php
$username = mysqli_real_escape_string($conn, $_POST['username']);
```

`vuln2`:
```php
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
```

### XSS 공격

`vuln0`: 출력 시 필터 없음 → <script>alert(1)</script> 삽입 가능

`vuln1`: htmlspecialchars() 부분 적용

`vuln2`: 전체 출력 escape + CSP 설정 적용

### CSRF 공격

`vuln0/vuln1`: <form action='delete.php' method='POST'><input name='id' value='1'></form> 자동 실행 가능

`vuln2`: form 내 csrf_token 생성 후 세션과 비교


## 리팩토링 우선순위

### 설정 파일

config/config.php

models/DB.php

### 모델

models/User.php (password_hash 적용, SQL Injection 방지)

models/Post.php

### 컨트롤러

AuthController.php (로그인, 회원가입, 세션 보호 강화)

PostController.php (작성자 검증, 파일 업로드 필터링)

AdminController.php (관리자 인증 필수)

### 라우터 페이지

login.php, register.php

write.php, view.php, mypage.php, admin/index.php

