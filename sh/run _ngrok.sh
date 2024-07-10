NGROK_STATIC_CMD=$(grep NGROK_STATIC_CMD ./.env | cut -d '=' -f 2- | tr -d '"')
${NGROK_STATIC_CMD}