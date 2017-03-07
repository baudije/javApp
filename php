FROM centos:7
MAINTAINER jeanmarc

#Install PHP layer
RUN rpm -Uvh https://dl.fedoraproject.org/pub/epel/epel-release-latest-7.noarch.rpm && \
    rpm -Uvh https://mirror.webtatic.com/yum/el7/webtatic-release.rpm \
    yum install -y php70w-fpm php70w-opcache

#Install nginx layer
RUN yum install -y gc gcc gcc-c++ pcre-devel zlib-devel make wget openssl-devel \
    libxml2-devel libxslt-devel gd-devel perl-ExtUtils-Embed GeoIP-devel gperftools \
    gperftools-devel libatomic_ops-devel perl-ExtUtils-Embed && \
    wget "http://nginx.org/download/nginx-1.8.1.tar.gz" && \
    tar -xvf nginx-1.8.1.tar.gz && \
    cd nginx-1.8.1 && \
    ./configure --add-module=../naxsi/naxsi_src \
       --add-module=../nginx_modules/headers-more-nginx-module \
       --user=nginx \
       --group=nginx \
       --prefix=/etc/nginx \
       --sbin-path=/usr/sbin/nginx \
       --conf-path=/etc/nginx/nginx.conf \
       --pid-path=/var/run/nginx.pid \
       --lock-path=/var/run/nginx.lock \
       --error-log-path=/var/log/nginx/error.log \
       --http-log-path=/var/log/nginx/access.log \
       --with-http_gzip_static_module \
       --with-http_stub_status_module \
       --with-http_ssl_module \
       --with-pcre-jit \
       --with-file-aio \
       --with-http_sub_module \
       --with-http_realip_module \
       make && \
       make install && \
       nginx -t 
       
CMD nginx -c /etc/shared_conf/nginx/nginx.conf
