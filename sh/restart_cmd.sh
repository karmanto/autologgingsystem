#!/bin/bash

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

        sudo sed -i "/^\s*ssid=/s/.*/    ssid=\"$SSID\"/" "$WPA_SUPPLICANT_CONF"
        if [ $PSK ]; then
            sudo sed -i "/^\s*psk=/d" "$WPA_SUPPLICANT_CONF"
            sudo sed -i "/^\s*ssid=\"$SSID\"/a\    key_mgmt=NONE" "$WPA_SUPPLICANT_CONF"
        else
            sudo sed -i "/^\s*key_mgmt=NONE/d" "$WPA_SUPPLICANT_CONF"
            sudo sed -i "/^\s*psk=/s/.*/    psk=\"$PSK\"/" "$WPA_SUPPLICANT_CONF"
        fi

        rm "$WIFI_FLAG_FILE"

        sudo ifdown wlan0 && sleep 2 && sudo ifup wlan0
    fi

    sleep 2
done
