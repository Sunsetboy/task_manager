#!/bin/sh

[ ! -e /etc/nginx/ssl ] && mkdir /etc/nginx/ssl

if [ ! -f "${DOMAIN}.key" ]; then
   DOMAIN="docker"
fi

mv /etc/nginx/ssl/${DOMAIN}.key /etc/nginx/ssl/key.key
mv /etc/nginx/ssl/${DOMAIN}.crt /etc/nginx/ssl/key.crt

rm -Rf /etc/nginx/conf.d/default.conf

if ping -c 1 -W 1 tasks; then
echo "
# task manager domain settings
server {
    listen       80;
    listen       443 ssl;
    index index.php;

    server_name  ${TASKS_HOST_NAME};
    root /var/www/tasks/public;

    ssl_certificate /etc/nginx/ssl/key.crt;
    ssl_certificate_key /etc/nginx/ssl/key.key;

    access_log /var/log/nginx/tasks_access.log;
    error_log /var/log/nginx/tasks_error.log;
    client_max_body_size 15m;

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass tasks:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME     \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO           \$fastcgi_path_info;
        fastcgi_read_timeout 300;
    }

    location / {
       proxy_set_header Host \$host;
       proxy_set_header   X-Real-IP        \$remote_addr;
       proxy_set_header   X-Forwarded-For        \$remote_addr;

       try_files \$uri \$uri/ /index.php?\$query_string;
    }
}
" >> /etc/nginx/conf.d/nginx.default.conf
fi