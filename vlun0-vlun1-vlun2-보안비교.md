
# 📄 PHP 게시판 보안 수준 단계별 비교 보고서 (`vlun0` → `vlun1` → `vlun2`)

## 개요

본 문서는 PHP 기반 게시판 시스템의 개발 보안 적용 수준을 3단계(`vlun0`, `vlun1`, `vlun2`)로 구분하여 비교 분석하고, 각 단계별로 보안 요소를 어떻게 적용하고 강화해나갈 수 있는지를 파일/함수/기능 단위로 제시한다.

## 보안 수준 정의

| 보안 수준 | 설명 |
|-----------|------|
| 🔴 `vlun0` (최악) | 보안이 전혀 적용되지 않은 개발 초기 버전. 대부분의 공격에 취약. |
| 🟡 `vlun1` (보안 도입) | 보안 요소 일부 적용, 여전히 취약하지만 공격 난이도 상승. |
| 🟢 `vlun2` (서비스 수준) | 보안 원칙 전반 적용. 실제 운영 환경 배포 가능 수준. |

## 🔐 주요 보안 요소별 비교표

| 항목 | `vlun0` | `vlun1` | `vlun2` |
|------|---------|---------|---------|
| SQL Injection 방지 | ❌ 전혀 없음 | ⚠️ `real_escape_string()` 일부 사용 | ✅ `prepare()` + `bind_param()` 전면 적용 |
| XSS 방지 | ❌ 전혀 없음 | ✅ `htmlspecialchars()` 출력 시 부분 적용 | ✅ 모든 출력 이스케이프 처리 및 Content Security Policy 적용 |
| CSRF 방지 | ❌ 없음 | ❌ 없음 | ✅ 토큰 기반 CSRF 방지 적용 |
| 세션 보안 | ❌ 무방비 | ⚠️ `session_regenerate_id()` 일부 적용 | ✅ `httponly`, `secure`, `strict_mode` 등 세션 하이재킹 방지 설정 포함 |
| 인증/인가 | ❌ 무제한 접근 가능 | ✅ 관리자 페이지 진입 시 세션 체크 | ✅ Role-based Access Control(RBAC) 기반 권한 분리 |
| 파일 업로드 제한 | ❌ 없음 (`.php`도 가능) | ✅ 확장자 제한 (`jpg`, `png`, `pdf`) | ✅ MIME 검증 + 업로드 실행 제한 `.htaccess` 적용 |
| 비밀번호 저장 | ❌ 평문 저장 | ✅ `password_hash()` 사용 | ✅ `argon2id` 또는 `bcrypt`로 해싱 + salt 적용 |
| 로그 관리 | ❌ 없음 또는 단순 파일 출력 | ✅ 로그인 로그 기록 | ✅ 사용자 행위 + 에러 로깅 + 파일 접근 제어 |
| 오류 메시지 | ❌ 내부 에러 직접 노출 | ✅ 사용자용 메시지와 구분 | ✅ 로깅과 사용자 출력 분리, 정보 유출 차단 |
| 관리자 우회 방지 | ❌ id=1 강제 | ✅ `is_admin` 체크 | ✅ DB 기반 인증 + API 보호 (JWT, CSRF) |

## 📂 파일 단위 보안 비교

### 1. `/config/config.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | DB 정보 하드코딩, 세션만 시작 |
| `vlun1` | 세션 보안 설정 도입 (`session.cookie_httponly`, `use_strict_mode`) |
| `vlun2` | DB 정보 외부화(`.env`), HTTPS 강제, 에러 처리 분리, 보안 헤더 포함 |

### 2. `/app/models/DB.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | `mysqli` 직접 연결 + `die()` 사용 |
| `vlun1` | 에러 메시지 숨김, 로그 기록 추가 |
| `vlun2` | `mysqli_report()` 기반 예외 + 로깅 시스템 연동 + 커넥션 재사용 구조 적용 |

### 3. `/app/controllers/AuthController.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | 비밀번호 평문, 인증 우회 가능, 세션 고정 |
| `vlun1` | `password_hash()`, `session_regenerate_id()` 추가, 간단한 관리자 인증 |
| `vlun2` | `password_verify()`, CSRF 방어, 2FA(옵션), 인증 로그 및 시도 제한 도입 |

### 4. `/app/controllers/PostController.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | 작성자 검증 없음, 파일 검증 없음 |
| `vlun1` | 사용자 ID 비교 추가, 업로드 확장자 필터링 |
| `vlun2` | `MIME` 체크, 실제 소유자 확인, 업로드 디렉토리 실행 제한 적용 |

### 5. `/app/models/User.php`, `/Post.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | SQL Injection, 평문 비밀번호 |
| `vlun1` | `password_hash()`, `real_escape_string()` |
| `vlun2` | Prepared Statement 전환, 관리자 우회 방지 로직 강화 |

### 6. `/public/*.php` (페이지 단위)

#### `login.php`, `register.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | CSRF 없음, XSS 가능 |
| `vlun1` | `htmlspecialchars()` 적용, 세션 고정 방지 |
| `vlun2` | CSRF 토큰, CAPTCHA, 실패 횟수 제한 도입 |

#### `write.php`, `view.php`, `mypage.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | 사용자 검증 없음, 파일 필터링 없음 |
| `vlun1` | 로그인 체크, 업로드 제한 |
| `vlun2` | 접근 권한 분리, 정적 업로드 분리, 자동 로깅 |

#### `admin/index.php`

| 수준 | 설명 |
|------|------|
| `vlun0` | 세션 확인 없음 |
| `vlun1` | `is_admin` 체크 |
| `vlun2` | 관리자 권한 분리 라우팅, RBAC, 로깅, 관리자 비밀번호 이중 확인 등 강화 |

## ✅ 보안 추천 단계별 가이드 요약

| 단계 | 실제 적용 권장 시점 | 핵심 전략 |
|------|------------------|----------|
| `vlun0` | **보안 교육용** 시연 목적 | 공격 테스트 및 탐지 실습 |
| `vlun1` | **학부 과제, 내부 훈련** | 일부 방어 기법만 도입하여 실전 대비 |
| `vlun2` | **운영 서비스 및 배포** | 완전한 보안 환경 기반, 외부 노출 가능 |

## 🔚 결론 및 다음 단계

- 현재 `vlun0` 버전은 **대부분의 웹 해킹 실습에 노출**되어 있어, 보안 적용의 출발점 역할을 충실히 수행함
- `vlun1`은 **입력값 필터링, 세션 보안, 인증 우회 방지 등 최소한의 방어 기법을 적용**하여 기본 보안 체계를 갖춤
- 최종 `vlun2`는 실제 서비스 운영 환경에서 필요한 **CSRF 방지, MIME 필터링, 로깅, RBAC** 등 고급 기법을 통합한 구조로 확장될 예정
