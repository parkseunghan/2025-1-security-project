FROM php:7.4-apache

# PHP 확장 설치 (mysqli만 사용)
RUN docker-php-ext-install mysqli

# Apache rewrite 모듈 활성화
RUN a2enmod rewrite

# SSH 설치 및 설정
RUN apt-get update && apt-get install -y openssh-server && \
    mkdir /var/run/sshd && \
    useradd -m hacker && echo "hacker:hacker123" | chpasswd && \
    echo "PermitRootLogin yes" >> /etc/ssh/sshd_config && \
    echo "PasswordAuthentication yes" >> /etc/ssh/sshd_config

# 업로드 설정 적용
COPY .docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

# Apache 가상호스트 설정 복사
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# 컨테이너 내 uploads 폴더 생성 (로컬에서 ./web/uploads 마운트될 것)
RUN mkdir -p /var/www/html/public/uploads && chmod -R 777 /var/www/html/public/uploads

# SSH(22), 웹서버(80) 포트 오픈
EXPOSE 80 22

# 웹서버 + SSH 함께 실행
CMD service ssh start && apache2-foreground
