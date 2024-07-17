!/bin/bash

NGROK_FILE="../storage/app/ngrok_static.txt"
WIFI_FLAG_FILE="../storage/app/wifi_client_flag.txt"
WPA_SUPPLICANT_CONF="/etc/wpa_supplicant/wpa_supplicant.conf"

while true; do
    if [ -f "$NGROK_FILE" ]; then
        SECOND_LINE=$(sed -n '2p' "$NGROK_FILE")

        if [ "$SECOND_LINE" -eq 1 ]; then
            sed -i '2s/.*/0/' "$NGROK_FILE"
            sudo reboot
        fi
    fi

    if [ -s "$WIFI_FLAG_FILE" ]; then
        SSID=$(sed -n '1p' "$WIFI_FLAG_FILE")
        PSK=$(sed -n '2p' "$WIFI_FLAG_FILE")

        sed -i "s/^\s*ssid=.*/    ssid=\"$SSID\"/" "$WPA_SUPPLICANT_CONF"
        sed -i "s/^\s*psk=.*/    psk=\"$PSK\"/" "$WPA_SUPPLICANT_CONF"

        rm "$WIFI_FLAG_FILE"

        sudo reboot
    fi

    sleep 2
done