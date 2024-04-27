#!/bin/bash
chmod +x install.sh
# Make the script executable
chmod +x chatter.sh
echo CHATTER IS INSTALLING.
# Move the script to the root directory
sudo mv chatter.sh ~

# Install PHP
sudo apt update
sudo apt install -y php

# Install ngrok
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null
echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list
sudo apt update
sudo apt install -y ngrok

# Add the script to rc.local to run on startup
sudo sed -i '/^exit 0/d' /etc/rc.local
echo "./chatter.sh &" | sudo tee -a /etc/rc.local
echo "exit 0" | sudo tee -a /etc/rc.local

read -p "Enter your ngrok authentication token: " NGROK_AUTH_TOKEN
ngrok config add-authtoken "$NGROK_AUTH_TOKEN"
echo "Setup completed! chatter.sh will run on startup."
