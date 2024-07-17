import json
import time
import os
import subprocess
from dotenv import load_dotenv

load_dotenv()

if os.getenv('FAKE_CONFIG', 'false').lower() == 'true':
    import fake_config as config
else:
    import config

FILE_PATH = config.INFO_JSON_PATH
INTERVAL = 10
ONLINE_STATUS = False
PREV_TIME = 0
CURR_TIME = 0

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
        with open(FILE_PATH, 'r') as json_file:
            data = json.load(json_file)

        ONLINE_STATUS = get_online_status()

        data.update({
            "online_status": ONLINE_STATUS,
            "ifconfig": get_ifconfig_output()
        })

        with open(FILE_PATH, 'w') as json_file:
            json.dump(data, json_file, indent=4)

    except FileNotFoundError:
        data = {
            "online_status": "",
            "ifconfig": ""
        }
        with open(FILE_PATH, 'w') as monitor_file:
            json.dump(data, monitor_file, indent=4)

    except Exception as e:
        print(f"Error updating json: {e}")

while True:
    time.sleep(.5)
    #if ONLINE_STATUS:
        #config.RPI_OUTPUT_ARRAY[1].value = not config.RPI_OUTPUT_ARRAY[1].value
    #else:
        #config.RPI_OUTPUT_ARRAY[1].value = True

    CURR_TIME = int(time.time())
    if CURR_TIME - PREV_TIME > INTERVAL:
        PREV_TIME = CURR_TIME
        update_json()
