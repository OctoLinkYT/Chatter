#!/bin/bash
chmod +x install.sh
# Make the script executable
chmod +x chatter.sh
clear
echo C H A T T E R
echo [wholeworldcoding]
sleep 2
echo [c] - Moving [chatter.sh] to root...
# Move the script to the root directory
sudo mv chatter.sh ~
clear
sleep 1
echo [c] - Installing dependencies...

# Install PHP
sudo apt update
sudo apt install -y php
clear
sleep 1
echo [c] - Information for Next Steps
echo Next, we'll install NGROK, a tool that allows for free web hosting. You are required to make an ngrok account on their website. Then, find your authtoken. We'll ask you for it soon.
echo [press ENTER to continue]
read -r
clear
echo [c] - Installing ngrok...
# Install ngrok
curl -s https://ngrok-agent.s3.amazonaws.com/ngrok.asc | sudo tee /etc/apt/trusted.gpg.d/ngrok.asc >/dev/null
echo "deb https://ngrok-agent.s3.amazonaws.com buster main" | sudo tee /etc/apt/sources.list.d/ngrok.list
sudo apt update
sudo apt install -y ngrok
clear
echo [c] - Preparing auth...
echo 
read -p "Enter your ngrok authentication token: " NGROK_AUTH_TOKEN
# Add the script to rc.local to run on startup
sudo sed -i '/^exit 0/d' /etc/rc.local
echo "(sleep 30 && /home/<username>/chatter.sh) &" | sudo tee -a /etc/rc.local
echo "exit 0" | sudo tee -a /etc/rc.local
cleear
ngrok config add-authtoken "$NGROK_AUTH_TOKEN"
echo "Setup completed! chatter.sh will run on startup."
