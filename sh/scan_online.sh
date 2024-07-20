!/bin/bash

SCAN_FILE="../storage/app/scan_online.txt"

while true; do
    ifconfig > test.txt && ping -c 1 google.com >> $SCAN_FILE
    sleep 5
done
