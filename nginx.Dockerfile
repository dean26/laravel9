FROM nginx:latest

RUN mkdir -p /code
COPY ./code /code

RUN rm /etc/nginx/conf.d/default.conf
COPY ./default.conf /etc/nginx/conf.d/

WORKDIR /code
EXPOSE 80
