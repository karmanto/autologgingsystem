!/bin/bash

NGROK_FILE="../storage/app/ngrok_static.txt"

FIRST_LINE=$(sed -n '1p' "$NGROK_FILE")
echo "$FIRST_LINE"
${FIRST_LINE}
echo "Executed ngrok command: $FIRST_LINE"

