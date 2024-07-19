rm -f ../storage/app/monitor.json && 
sudo ../python/venv/bin/python ../python/main.py
sleep 10
sudo ../python/venv/bin/python ../python/scan_online.py
