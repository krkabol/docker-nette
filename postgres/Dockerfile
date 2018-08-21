FROM mdillon/postgis:10-alpine

#workaround pro Alpine
RUN apk --no-cache add ca-certificates wget && \
    wget -q -O /etc/apk/keys/sgerrand.rsa.pub https://raw.githubusercontent.com/sgerrand/alpine-pkg-glibc/master/sgerrand.rsa.pub && \
    wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/2.25-r0/glibc-2.25-r0.apk && \
    wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/2.25-r0/glibc-bin-2.25-r0.apk && \
    wget https://github.com/sgerrand/alpine-pkg-glibc/releases/download/2.25-r0/glibc-i18n-2.25-r0.apk && \
    apk add glibc-bin-2.25-r0.apk glibc-i18n-2.25-r0.apk glibc-2.25-r0.apk

RUN xargs -i /usr/glibc-compat/bin/localedef -i cs_CZ -f UTF-8 cs_CZ.UTF-8

#jiná distro kromě Alpine:
#RUN localedef -i cs_CZ -c -f UTF-8 -A /usr/share/locale/locale.alias cs_CZ.UTF-8

ENV LANG cs_CZ.utf8
ENV LANGUAGE cs_CZ.utf8

COPY conf/init-user-db.sh /docker-entrypoint-initdb.d/