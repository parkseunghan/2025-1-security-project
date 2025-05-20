FROM php:7.4-apache

# PHP 확장 설치 (mysqli만 필요함)
RUN docker-php-ext-install mysqli

# 아파치 설정 수정 (rewrite 허용)
RUN a2enmod rewrite

# 업로드 폴더 생성 및 퍼미션 허술하게
RUN mkdir -p /var/www/html/uploads && chmod -R 777 /var/www/html/uploads

# 아파치 설정 복사 (vhost)
COPY .docker/apache/vhost.conf /etc/apache2/sites-available/000-default.conf

# PHP 업로드 관련 설정 (.ini)
COPY .docker/php/uploads.ini /usr/local/etc/php/conf.d/uploads.ini

CMD echo "✅ 서버가 실행되었습니다! 접속 주소: http://localhost:8080" && apache2ctl -D FOREGROUND
