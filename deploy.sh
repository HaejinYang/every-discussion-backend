#!/bin/bash
cd app/every-discussion-backend
git pull
docker compose --env-file .env.core down
docker compose --env-file .env.core up -d --build
