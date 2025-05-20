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