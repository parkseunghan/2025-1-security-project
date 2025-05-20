# 🛡️ 2025-1 Security Project - vuln0

> 매우 취약한 보안이 1도 없는 버전의 PHP 게시판

---

## 프로젝트 개요

이 프로젝트는 보안이 전혀 적용되지 않은 버전부터 점차 보안을 적용한 PHP 기반 게시판을 직접 개발하여  
실제 취약점 실습 및 시큐어코딩 전환 과정을 학습하는 데 목적이 있습니다.

본 버전은 **vuln0 (취약점 제거 전 버전)** 으로, 실습 목적상 다양한 보안 허점이 존재합니다.

---

## ⚙️ 실행 방법 (Docker 기반)

```bash
# 1. 빌드 및 실행
docker compose up --build

# 2. 웹 브라우저 접속
http://localhost:8080


# 3. 내리기
docker compose down -v
```

### logs 저장 안되면
```sh
mkdir -p logs
chmod 777 logs
```

---

## 보고서 (예시)

| 문서명 | 주요 내용 |
| ------------- | ----------------------------------------------------------- |
| **요구사항 정의서** | - 실험 대상 취약점<br> - PHP 환경 사양<br> - 기능 구성 (게시판, 로그인 등) |
| **설계서** | - 시스템 구성도<br> - 미적용/적용 비교 아키텍처<br> - 시큐어 코딩 로직 도식화 |
| **테스트 시나리오** | - 각 취약점 공격 절차 (요청, 입력값, 기대 응답 등)<br> - 공격 성공 여부 기록 양식 |
| **실험 결과 보고서** | - 공격 성공률 비교표<br> - 응답 분석 (HTTP 코드, 화면 변화 등)<br> - 차트 기반 시각화 |
| **최종 보고서** | - 전체 개발/분석 흐름 요약<br> - 보안 적용 효과 분석<br> - 활용 방안 및 한계 |


## 폴더 구조

```sh
2025-1-security-project/
│
├── 📁 config/
│   └── config.php
│
├── 📁 controllers/
│   ├── AuthController.php
│   ├── PostController.php
│   └── VULNERABILITY_LIST.md
│
├── 📁 models/
│   ├── DB.php
│   ├── Post.php
│   ├── User.php
│   └── VULNERABILITY_LIST.md
│
├── 📁 public/
│   ├── delete.php
│   ├── download.php
│   ├── edit.php
│   ├── index.php
│   ├── login.php
│   ├── logout.php
│   ├── register.php
│   ├── view.php
│   ├── write.php
│   └── VULNERABILITY_LIST.md
│
├── .gitignore
├── db_table.sql
└── README.md

```

## ✅ 기능 목록

- 회원가입
- 로그인 / 로그아웃
- 게시글 작성 / 수정 / 삭제
- 파일 업로드 및 다운로드
- 게시글 검색 기능
- 각 기능 별 **취약점 존재**

---


## 확장
| 기능                  | 설명                                | 취약점 존재 여부       |
| ------------------- | --------------------------------- | --------------- |
| 1. 마이페이지 (회원 정보 수정) | 사용자는 로그인 후 자신의 닉네임, 비밀번호 등을 수정 가능 | ✅ XSS, 인증 우회    |
| 2. 회원가입 개선          | 아이디 중복 확인, 닉네임 중복 확인, 비밀번호 재입력 비교 | ✅ JS 조작으로 우회 가능 |
| 3. 관리자 페이지          | 관리자 계정은 모든 사용자 정보 및 글 확인/삭제 가능    | ✅ 관리자 권한 탈취 가능  |
| 4. Seed 관리자 계정      | `seed.php` 또는 SQL에 직접 삽입          | ✅ 노출되면 관리자 탈취됨  |

### 🧩 앞으로 작성해야 할 파일들
📁 public/
mypage.php: 회원정보 수정 폼 (닉네임, 비밀번호)

admin.php: 관리자 전용 페이지

check_username.php: 아이디 중복 체크용 AJAX 응답

check_nickname.php: 닉네임 중복 체크용 AJAX 응답

📁 controllers/
UserController.php: 마이페이지, 관리자 페이지 처리 추가

📁 models/
User.php: updateUser(), isUsernameTaken(), isNicknameTaken() 등 함수 추가

📁 views/
auth/mypage.php, admin/dashboard.php 등 추가 가능

📁 _docker/mysql/
init.sql: 관리자 계정 seed 삽입