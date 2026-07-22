FROM postgres:16

ENV TZ=America/Lima
ENV PGTZ=America/Lima

COPY init.sql /docker-entrypoint-initdb.d/init.sql
