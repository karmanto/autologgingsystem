NGROK_STATIC_CMD=$(grep NGROK_STATIC_CMD ./.env | cut -d '=' -f 2-)

rm ./storage/app/monitor.json && 
./python/venv/bin/python ./python/main.py &
$NGROK_STATIC_CMD
