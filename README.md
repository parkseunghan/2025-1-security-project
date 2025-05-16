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
