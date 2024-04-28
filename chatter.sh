#!/bin/bash

# DON'T FORGET TO PUT THIS IN ROOT!
/usr/local/bin/ngrok http --domain=morally-elegant-pig.ngrok-free.app 8080 &

# Start PHP server
php -S localhost:8080 -t chatter

clear
echo C H A T T E R
echo [wholeworldcoding]
echo 
