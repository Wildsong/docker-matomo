FROM matomo:latest

# Add the maxmind module for apache

RUN apt-get update && \
    apt-get install -y apache2-dev libmaxminddb-dev
#    apt-get install -y build-essential

RUN mkdir /module_build
ADD https://github.com/maxmind/mod_maxminddb/releases/download/1.2.0/mod_maxminddb-1.2.0.tar.gz /module_build

WORKDIR /module_build
RUN tar xzvf mod_maxminddb-1.2.0.tar.gz && \
    cd mod_maxminddb-1.2.0 && \
    ./configure && \
    make && \
    make install
    
WORKDIR /var/www/html

