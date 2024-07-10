NGROK_STATIC_CMD=$(grep NGROK_STATIC_CMD ./.env | cut -d '=' -f 2- | tr -d '"')

rm ./storage/app/monitor.json && 
./python/venv/bin/python ./python/main.py &

sleep 30

${NGROK_STATIC_CMD}
