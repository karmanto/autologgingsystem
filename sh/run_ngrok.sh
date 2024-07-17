!/bin/bash

NGROK_FILE="../storage/app/ngrok_static.txt"

if [ -s "$NGROK_FILE" ]; then
    FIRST_LINE=$(sed -n '1p' "$NGROK_FILE")
    ${FIRST_LINE}
fi

