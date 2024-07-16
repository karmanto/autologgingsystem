import json
import time
import socket
import uuid
import os
import subprocess
from dotenv import load_dotenv

load_dotenv()

if os.getenv('FAKE_CONFIG', 'false').lower() == 'true':
    import fake_config as config
else:
    import config

file_path = config.INFO_JSON_PATH

def get_online_status():
    try:
        response = os.system("ping -c 1 google.com > /dev/null 2>&1")
        return response == 0
    except Exception as e:
        print(f"Error checking online status: {e}")
        return False

def get_ifconfig_output():
    try:
        ifconfig_output = subprocess.check_output("ifconfig", shell=True, text=True)
        return ifconfig_output
    except Exception as e:
        print(f"Error getting ifconfig output: {e}")
        return None

def update_json():
    try:
        with open(file_path, 'r') as json_file:
            data = json.load(json_file)

        data.update({
            "online_status": get_online_status(),
            "ifconfig": get_ifconfig_output()
        })

        with open(file_path, 'w') as json_file:
            json.dump(data, json_file, indent=4)

    except FileNotFoundError:
        data = {
            "online_status": "",
            "ifconfig": ""
        }
        with open(file_path, 'w') as monitor_file:
            json.dump(data, monitor_file, indent=4)

    except Exception as e:
        print(f"Error updating json: {e}")

interval = 5

while True:
    update_json()
    time.sleep(interval)
