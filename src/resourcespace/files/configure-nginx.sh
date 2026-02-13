#!/bin/bash
# This script sets the Nginx server block

# Detect PHP version dynamically
PHP_VERSION=$(php -r 'echo implode(".", array_slice(explode(".", phpversion()), 0, 2));')
PHP_FPM_SOCK="/run/php/php${PHP_VERSION}-fpm.sock"

sudo rm /etc/nginx/sites-enabled/default
cat <<EOF | sudo tee /etc/nginx/sites-available/resourcespace
# Public hostname as seen by users (CloudFront alias)
map \$http_cloudfront_forwarded_proto \$rs_proto {
    default \$http_x_forwarded_proto;  # fallback if ALB sets it
    https   https;
    http    http;
}
server {
    set \$public_host "dev-images.bcparks.ca";
    set \$public_proto "https";
    set \$public_port 443;

    listen 80 default_server;
    server_name _;

    client_max_body_size 2G;
    client_body_timeout 1200s;

    root /var/www/resourcespace;
    index index.php index.html;
    
    location / {
        try_files \$uri \$uri/ /index.php?\$args;
    }

    location /health-check.php {
        try_files /health-check.php =404;
    }

    location = /bcgovhealthcheck {
        return 200 "ok";
        add_header Content-Type text/plain;
    }

    location /filestore/ {
        add_header Access-Control-Allow-Origin *;
    }

    location /pages/upload_batch.php/files/ {
        # Allow TUS headers
        add_header Access-Control-Allow-Origin *;
        add_header Access-Control-Allow-Methods "POST, HEAD, OPTIONS, PATCH";
        add_header Access-Control-Allow-Headers "Origin, X-Requested-With, Content-Type, Accept, Tus-Resumable, Upload-Offset, Upload-Metadata";

        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCK};
        fastcgi_param SCRIPT_FILENAME \$document_root/pages/upload_batch.php;
        fastcgi_param PATH_INFO \$uri;
        include fastcgi_params;
        
        # Pass X-Forwarded headers from ALB to PHP
        fastcgi_param HTTP_X_FORWARDED_PROTO \$http_x_forwarded_proto;
        fastcgi_param HTTP_X_FORWARDED_FOR \$proxy_add_x_forwarded_for;
        fastcgi_param HTTP_X_FORWARDED_HOST \$http_x_forwarded_host;
    }

    location /plugins/simplesaml/ {
        alias /var/www/resourcespace/plugins/simplesaml/;
        index index.php;

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:${PHP_FPM_SOCK};
            fastcgi_param SCRIPT_FILENAME \$request_filename;
            include fastcgi_params;

            fastcgi_read_timeout 1200;
            fastcgi_connect_timeout 1200;
            fastcgi_send_timeout 1200;

            # Force PHP to see the public URL (because CloudFront can't forward Host)
            fastcgi_param HTTP_HOST              \$public_host;
            fastcgi_param SERVER_NAME            \$public_host;
            fastcgi_param SERVER_PORT            \$public_port;
            fastcgi_param HTTPS                  "on";

            fastcgi_param HTTP_X_FORWARDED_PROTO \$public_proto;
            fastcgi_param HTTP_X_FORWARDED_HOST  \$public_host;
            fastcgi_param HTTP_X_FORWARDED_PORT  \$public_port;
            fastcgi_param REQUEST_SCHEME \$public_proto;
        }

        # Pass additional path info to PHP
        location ~ ^/plugins/simplesaml/lib/public/module\.php(/.*)?$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:${PHP_FPM_SOCK};
            fastcgi_param SCRIPT_FILENAME /var/www/resourcespace/plugins/simplesaml/lib/public/module.php;
            fastcgi_param PATH_INFO \$1;
            include fastcgi_params;

            fastcgi_read_timeout 1200;
            fastcgi_connect_timeout 1200;
            fastcgi_send_timeout 1200;

            # --- Force public URL identity for SimpleSAML ---
            fastcgi_param HTTP_HOST              \$public_host;
            fastcgi_param SERVER_NAME            \$public_host;
            fastcgi_param SERVER_PORT            \$public_port;
            fastcgi_param HTTPS                  "on";

            fastcgi_param HTTP_X_FORWARDED_HOST  \$public_host;
            fastcgi_param HTTP_X_FORWARDED_PROTO \$public_proto;
            fastcgi_param HTTP_X_FORWARDED_PORT  \$public_port;
            fastcgi_param REQUEST_SCHEME \$public_proto;
        }
    }

    # PHP processing
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:${PHP_FPM_SOCK};
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_read_timeout 1200;
        fastcgi_connect_timeout 1200;
        fastcgi_send_timeout 1200;

        # Force PHP to see the public URL everywhere (workaround for non-forwarded Host)
        fastcgi_param HTTP_HOST              \$public_host;
        fastcgi_param SERVER_NAME            \$public_host;
        fastcgi_param SERVER_PORT            \$public_port;
        fastcgi_param HTTPS                  "on";

        fastcgi_param HTTP_X_FORWARDED_HOST  \$public_host;
        fastcgi_param HTTP_X_FORWARDED_PROTO \$public_proto;
        fastcgi_param HTTP_X_FORWARDED_PORT  \$public_port;
    }

    # Restrict access to hidden files
    location ~ /\.ht {
        deny all;
    }
}
EOF

# Enable the Nginx configuration
sudo ln -s /etc/nginx/sites-available/resourcespace /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx