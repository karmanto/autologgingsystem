import MySQLdb
import board
import busio
import digitalio
import os
from datetime import datetime, date, timedelta
from dotenv import load_dotenv
from adafruit_mcp230xx.mcp23017 import MCP23017

load_dotenv()

_I2C_PIN = busio.I2C(board.SCL, board.SDA)
_OUT1 = board.D23
_OUT2 = board.D24
_OUT3 = board.D4
_RPI_PIN_ARRAY = [
    board.D5, board.D6, board.D7, board.D8,
    board.D9, board.D10, board.D11, board.D12,
    board.D13, board.D27, board.D25, board.D16,
    board.D17, board.D18, board.D19, board.D20,
    board.D21, board.D22
]

FIELD_STRING = "cbc1,cbc2,prs1,prs2,prs3,prs4,prs5,prs6,prs7,prs8,dtr1,dtr2,dtr3,dtr4,dtr5,dtr6,dtr7,dtr8,spr0,spr1,spr2,spr3,spr4,spr5,spr6,spr7,spr8,spr9,spr10,spr11,spr12,spr13,spr14,spr15,spr16,spr17,spr18,spr19,spr20,spr21,spr22,spr23,spr24,spr25,spr26,spr27,spr28,spr29,spr30,spr31,spr32,spr33,spr34,spr35,spr36,spr37,spr38,spr39,spr40,spr41,spr42,spr43,spr44,spr45"
RPI_OUTPUT_ARRAY = [digitalio.DigitalInOut(pin) for pin in [_OUT1, _OUT2, _OUT3]]
RPI_INPUT_ARRAY = [digitalio.DigitalInOut(pin) for pin in _RPI_PIN_ARRAY]
JSON_PATH = "../storage/app/monitor.json"
INFO_JSON_PATH = "../storage/app/info.json"
SETTINGS_JSON_PATH = "../storage/app/settings.json"
USER_JSON_PATH = '../storage/app/user_notifications.json'

def get_db_connection():
    try:
        db = MySQLdb.connect(
                host=os.getenv("DB_HOST"),
                user=os.getenv("DB_USER"),
                passwd=os.getenv("DB_PASSWORD"),
                db=os.getenv("DB_NAME")
            )
        return True, db
    except:
        return False, None

def init_rpi_gpio():
    for OUTPUT in RPI_OUTPUT_ARRAY:
        OUTPUT.direction = digitalio.Direction.OUTPUT
        OUTPUT.value = False

    for INPUT in RPI_INPUT_ARRAY:
        INPUT.direction = digitalio.Direction.INPUT
        INPUT.pull = digitalio.Pull.UP

def init_mcp(ADDR):
    try:
        MCP = MCP23017(_I2C_PIN, address=ADDR)
        PINS = [MCP.get_pin(i) for i in range(16)]

        for PIN in PINS:
            PIN.direction = digitalio.Direction.INPUT
            PIN.pull = digitalio.Pull.UP

        return True, PINS

    except:
        return False, []
