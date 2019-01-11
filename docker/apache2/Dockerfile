FROM httpd:2.4.33-alpine
RUN apk update; \
    apk upgrade;
COPY conf/magento2.conf /usr/local/apache2/conf/magento2.conf
RUN echo "Include /usr/local/apache2/conf/magento2.conf" \