## 설정 방법

1. sql DB 생성 및 테이블 추가(db_table.sql)
2. config_example.php의 파일 명을 config.php로 변경 혹은 생성 후 수정


## 보고서 (예시)

| 문서명 | 주요 내용 |
| ------------- | ----------------------------------------------------------- |
| **요구사항 정의서** | - 실험 대상 취약점<br> - PHP 환경 사양<br> - 기능 구성 (게시판, 로그인 등) |
| **설계서** | - 시스템 구성도<br> - 미적용/적용 비교 아키텍처<br> - 시큐어 코딩 로직 도식화 |
| **테스트 시나리오** | - 각 취약점 공격 절차 (요청, 입력값, 기대 응답 등)<br> - 공격 성공 여부 기록 양식 |
| **실험 결과 보고서** | - 공격 성공률 비교표<br> - 응답 분석 (HTTP 코드, 화면 변화 등)<br> - 차트 기반 시각화 |
| **최종 보고서** | - 전체 개발/분석 흐름 요약<br> - 보안 적용 효과 분석<br> - 활용 방안 및 한계 |


## 폴더 구조

📁 /var/www/html/2025-1-security-project/
│
├── 📁 public/                     # 외부 진입점, static 파일 포함
│   ├── index.php                 # 메인 게시판
│   ├── login.php                 # 로그인 폼
│   ├── register.php              # 회원가입 폼
│   ├── write.php                 # 게시글 작성 폼
│   ├── view.php                  # 게시글 상세보기
│   ├── edit.php                  # 게시글 수정 폼
│   ├── assets/                   # 정적 파일 (css, js 등)
│
├── 📁 controllers/               # 요청 처리 (비즈니스 로직)
│   ├── AuthController.php
│   ├── PostController.php
│   └── UserController.php
│
├── 📁 models/                    # DB 접근 계층
│   ├── User.php
│   ├── Post.php
│   └── DB.php
│
├── 📁 views/                     # HTML 렌더링 (템플릿)
│   ├── layout/                   # header, footer
│   ├── auth/                     # 로그인, 회원가입
│   └── post/                     # 게시글 보기/쓰기
│
├── 📁 config/                    # 환경설정
│   ├── config.php               # DB 설정 (Git 제외)
│   └── config.php.example       # 예시 설정 (Git 포함)
│
├── 📁 middleware/                # 인증/권한 확인
│   └── auth.php
│
├── 📁 helpers/                   # 공통 함수
│   └── functions.php
│
├── 📁 uploads/                   # 업로드 파일 저장
│
├── .htaccess                     # 보안 설정 + URL 재작성
├── composer.json                # 외부 라이브러리 정의
└── README.md


## 기능별 매핑표 (취약점 제거 버전)

| 페이지/파일          | 기능     | 경로                      | 담당 Controller                | 설명             |
| --------------- | ------ | ----------------------- | ---------------------------- | -------------- |
| `index.php`     | 게시글 목록 | `/public/index.php`     | `PostController::index()`    | 전체 글 리스트 조회    |
| `login.php`     | 로그인    | `/public/login.php`     | `AuthController::login()`    | 세션 로그인 처리      |
| `register.php`  | 회원가입   | `/public/register.php`  | `AuthController::register()` | 유효성 검사 후 DB 저장 |
| `write.php`     | 글 작성   | `/public/write.php`     | `PostController::create()`   | 사용자 글 DB 등록    |
| `view.php`      | 글 보기   | `/public/view.php?id=1` | `PostController::show()`     | 단일 글 조회        |
| `edit.php`      | 글 수정   | `/public/edit.php?id=1` | `PostController::edit()`     | 작성자 본인만 수정     |
| `delete` (POST) | 글 삭제   | POST 요청                 | `PostController::delete()`   | 작성자 본인만 삭제     |
| `uploads/`      | 파일 업로드 | 파일 링크                   | 내부 헬퍼 함수                     | 제한된 확장자만 허용    |


## 3. 기능별 보안 위협 요약 (취약점 제거 버전 대응 포함)

| 기능     | 취약점 유형                     | 대응 방법 (제거 방식)                                    |
| ------ | -------------------------- | ------------------------------------------------ |
| 로그인    | Brute Force, SQL Injection | 비밀번호 해시 (`password_hash`), Prepared Statement 사용 |
| 회원가입   | XSS, Weak Password         | 모든 입력 `htmlspecialchars()`, 최소 비밀번호 규칙 적용        |
| 글쓰기    | XSS, CSRF                  | HTML 이스케이프, CSRF Token 필드 적용 예정                  |
| 글보기    | XSS                        | 출력 시 `htmlspecialchars()` 처리                     |
| 글수정    | 권한 미검사, CSRF               | 세션 검사 + 작성자만 수정 가능 + CSRF Token 적용 예정            |
| 글삭제    | 권한 우회                      | 작성자 본인 확인 후 삭제 허용                                |
| 파일 업로드 | 파일 실행, MIME 조작             | 업로드 시 확장자 + MIME 검사, `.htaccess`로 PHP 실행 차단      |
| 세션 관리  | 세션 탈취                      | `session_regenerate_id(true)`, HttpOnly 쿠키 적용    |


### 참고: 제거된 취약점 종류
❌ 직접 쿼리 사용 (SELECT ... WHERE user = '$username') → Prepared Statement로 교체

❌ 입력 필터링 없음 → trim(), htmlspecialchars(), 정규식 사용

❌ 권한 미검사 → 세션 기반 인증 및 작성자 확인

❌ 파일 필터 없음 → 확장자 체크 + 실행 차단 (uploads/.htaccess)

❌ CSRF 대응 없음 → 추후 보안솔루션 적용 버전에서 CSRF Token 포함