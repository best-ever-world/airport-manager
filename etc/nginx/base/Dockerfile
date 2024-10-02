FROM nginx:latest as nginx_latest

COPY nginx.conf /etc/nginx/

RUN apt-get update \
    && apt-get install -y openssl curl

RUN mkdir -p /var/run/

ENV TZ=UTC
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone
