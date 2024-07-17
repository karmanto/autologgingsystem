!/bin/bash

NGROK_FILE="../storage/app/ngrok_static.txt"

if [ -f "$NGROK_FILE" ]; then
    SECOND_LINE=$(sed -n '2p' "$NGROK_FILE")

    if [ "$SECOND_LINE" -eq 1 ]; then
        sed -i '2s/.*/0/' "$NGROK_FILE"
        echo "Changed second line to 0."

        echo "Restarting..."
        sudo reboot
    else
        echo "Unexpected value in second line of $NGROK_FILE: $SECOND_LINE"
    fi
else
    echo "File $NGROK_FILE not found."
fi

while true; do
    SECOND_LINE=$(sed -n '2p' "$NGROK_FILE")

    if [ "$SECOND_LINE" -eq 1 ]; then
        sed -i '2s/.*/0/' "$NGROK_FILE"
        echo "Changed second line to 0."

        echo "Restarting..."
        sudo reboot
    fi

    sleep 2
done
