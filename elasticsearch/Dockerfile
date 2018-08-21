FROM docker.elastic.co/elasticsearch/elasticsearch-oss:6.3.2

COPY conf/docker-healthcheck /usr/local/bin/
#COPY --chown=elasticsearch:elasticsearch conf/elasticsearch.yml /usr/share/elasticsearch/config/

HEALTHCHECK CMD ["docker-healthcheck"]