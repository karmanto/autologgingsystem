rm -f ../storage/app/monitor.json && 
sudo ../python/venv/bin/python ../python/main.py & 
sudo ../python/venv/bin/python ../python/scan_online.py &
sudo ../python/venv/bin/python ../python/telegram_bot.py &
