#!/bin/bash

JSON_FILE="./storage/app/setup_cmd.json"

if ! command -v jq &> /dev/null
then
    echo "jq could not be found, installing jq..."
    sudo apt-get update && sudo apt-get install -y jq
fi

while true; do
    up_stat=$(jq '.setup_command' $JSON_FILE)

    if [ "$up_stat" = "true" ]; then
        echo "setup_command is true, proceeding with git pull and reboot..."
        git pull origin master
        jq '.setup_command = false' $JSON_FILE > tmp.$$.json && mv tmp.$$.json $JSON_FILE
        sudo reboot
        break
    fi
    sleep 2
done
