FROM node:12.16.1

# locale & timezone (Asia/Tokyo)
# https://github.com/moby/moby/issues/12084
ENV LANG C.UTF-8
ENV TZ Asia/Tokyo

WORKDIR /app

RUN apt update && \
    npm install -g npm && \
    npm install -g @vue/cli && \
    npm install -g @vue/cli-init

ENV HOST 0.0.0.0
EXPOSE 3000