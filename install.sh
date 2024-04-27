#!/bin/bash

# Make the script executable
chmod +x chatter.sh

# Move the script to the root directory
sudo mv chatter.sh /root/

# Install PHP
sudo apt update
sudo apt install -y php

# Install ngrok
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null
echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list
sudo apt update
sudo apt install -y ngrok

echo "Setup completed!"
