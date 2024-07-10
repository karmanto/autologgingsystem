rm ./storage/app/monitor.json & 
./python/venv/bin/python ./python/main.py
ngrok http --domain=open-useful-joey.ngrok-free.app 80
