#!/bin/bash

WIFI_FLAG_FILE="./storage/app/wifi_client_flag.txt"
WPA_SUPPLICANT_CONF="/etc/wpa_supplicant/wpa_supplicant.conf"

while true; do
    if [ -s "$WIFI_FLAG_FILE" ]; then
        SSID=$(sed -n '1p' "$WIFI_FLAG_FILE")
        PSK=$(sed -n '2p' "$WIFI_FLAG_FILE")

        sed -i "s/^\s*ssid=.*/    ssid=\"$SSID\"/" "$WPA_SUPPLICANT_CONF"
        sed -i "s/^\s*psk=.*/    psk=\"$PSK\"/" "$WPA_SUPPLICANT_CONF"

        > "$WIFI_FLAG_FILE"

        sudo reboot
    fi

    sleep 2
done
