#!/bin/bash
cd app/every-discussion-backend
git pull
sudo docker compose down
sudo docker compose up -d --build
