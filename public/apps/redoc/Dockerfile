FROM nginx:alpine
ENV API_URL=""
COPY nginx/default.conf /etc/nginx/conf.d/default.conf
COPY nginx/replace_env.sh /docker-entrypoint.d/replace_env.sh
RUN chmod +x /docker-entrypoint.d/replace_env.sh
COPY . /usr/share/nginx/html
COPY index.html /usr/share/index.html
