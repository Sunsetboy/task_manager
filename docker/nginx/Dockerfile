FROM nginx:alpine

RUN apk update && apk upgrade \
    apk add openssl \
    apk cache clean

COPY nginx.conf.sh /run
COPY ssl /etc/nginx/ssl/

CMD sh /run/nginx.conf.sh && \
    touch /tmp/stand.ready && \
    nginx -g 'daemon off;'