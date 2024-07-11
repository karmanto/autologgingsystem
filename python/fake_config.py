import MySQLdb
import os
from dotenv import load_dotenv
import time
import random

load_dotenv()

_OUT1 = 23
_OUT2 = 24
_OUT3 = 4
_RPI_PIN_ARRAY = [
    5, 6, 7, 8,
    9, 10, 11, 12,
    13, 27, 25, 16,
    17, 18, 19, 20,
    21, 22
]

FIELD_STRING = "cbc1,cbc2,prs1,prs2,prs3,prs4,prs5,prs6,prs7,prs8,dtr1,dtr2,dtr3,dtr4,dtr5,dtr6,dtr7,dtr8,spr0,spr1,spr2,spr3,spr4,spr5,spr6,spr7,spr8,spr9,spr10,spr11,spr12,spr13,spr14,spr15,spr16,spr17,spr18,spr19,spr20,spr21,spr22,spr23,spr24,spr25,spr26,spr27,spr28,spr29,spr30,spr31,spr32,spr33,spr34,spr35,spr36,spr37,spr38,spr39,spr40,spr41,spr42,spr43,spr44,spr45"

class FakeDigitalInOut:
    def __init__(self, initial_value):
        self._value = initial_value
        self._last_change_time = time.time()
        self._change_interval = 5.0

    @property
    def value(self):
        current_time = time.time()
        if current_time - self._last_change_time >= self._change_interval:
            self._value = random.choice([True, False])
            self._last_change_time = current_time
        return self._value

    @value.setter
    def value(self, new_value):
        self._value = new_value

    @property
    def direction(self):
        return 'input'

    @direction.setter
    def direction(self, new_direction):
        pass

    @property
    def pull(self):
        return 'up'

    @pull.setter
    def pull(self, new_pull):
        pass

RPI_OUTPUT_ARRAY = [FakeDigitalInOut(False) for _ in range(3)]
RPI_INPUT_ARRAY = [FakeDigitalInOut(False) for _ in _RPI_PIN_ARRAY]
JSON_PATH = "../storage/app/monitor.json"

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
        OUTPUT.direction = 'output'
        OUTPUT.value = False

    for INPUT in RPI_INPUT_ARRAY:
        INPUT.direction = 'input'
        INPUT.pull = 'up'

def init_mcp(ADDR):
    try:
        PINS = [FakeDigitalInOut(False) for _ in range(16)]
        return True, PINS
    except:
        return False, []
