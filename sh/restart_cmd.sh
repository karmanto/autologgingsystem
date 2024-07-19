!/bin/bash

NGROK_FILE="../storage/app/ngrok_static.txt"
WPA_SUPPLICANT_SOURCE="../storage/app/wpa_supplicant.conf"
WPA_SUPPLICANT_DEST="/etc/wpa_supplicant/wpa_supplicant.conf"

while true; do
    if [ -f "$NGROK_FILE" ]; then
        SECOND_LINE=$(sed -n '2p' "$NGROK_FILE")

        if [ "$SECOND_LINE" -eq 1 ]; then
            sed -i '2s/.*/0/' "$NGROK_FILE"
            sudo reboot
        fi
    fi

    if [ -s "$WPA_SUPPLICANT_SOURCE" ]; then
        rm "$WIFI_FLAG_FILE"

        sudo cp "$WPA_SUPPLICANT_SOURCE" "$WPA_SUPPLICANT_DEST"
        sudo ifdown wlan0 && sleep 2 && sudo ifup wlan0
    fi

    sleep 2
done
