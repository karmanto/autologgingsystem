import MySQLdb
import board
import busio
import digitalio
import os
from datetime import datetime, date, timedelta
from dotenv import load_dotenv

load_dotenv()

FIELD_TABLE_ARRAY = ["cbc1","cbc2","prs1","prs2","prs3","prs4","prs5","prs6","prs7","prs8","dtr1","dtr2","dtr3","dtr4","dtr5","dtr6","dtr7","dtr8", "spr0","spr1","spr2","spr3","spr4","spr5","spr6","spr7","spr8","spr9","spr10","spr11","spr12","spr13","spr14","spr15","spr16","spr17","spr18","spr19","spr20","spr21","spr22","spr23","spr24","spr25","spr, 26","spr27","spr28","spr29","spr30","spr31","spr32","spr33","spr34","spr35","spr36","spr37","spr38","spr39","spr40","spr41","spr42","spr43","spr44","spr45"]
OUT1 = board.D4
OUT2 = board.D17
OUT3 = board.D27
RPI_PIN_ARRAY = [
    board.D5, board.D6, board.D13, board.D19,
    board.D26, board.D21, board.D20, board.D16,
    board.D12, board.D7, board.D8, board.D25,
    board.D24, board.D23, board.D18, board.D22,
    board.D27, board.D15
]

RPI_OUTPUT_ARRAY = [digitalio.DigitalInOut(pin) for pin in [OUT1, OUT2, OUT3]]
RPI_INPUT_ARRAY = [digitalio.DigitalInOut(pin) for pin in RPI_PIN_ARRAY]

def get_db_connection():
    return MySQLdb.connect(
        host=os.getenv("DB_HOST"),
        user=os.getenv("DB_USER"),
        passwd=os.getenv("DB_PASSWORD"),
        db=os.getenv("DB_NAME")
    )

def init_rpi_gpio():
    for OUTPUT in RPI_OUTPUT_ARRAY:
        OUTPUT.direction = digitalio.Direction.OUTPUT
        OUTPUT.value = False

    for INPUT in RPI_INPUT_ARRAY:
        INPUT.direction = digitalio.Direction.INPUT
        INPUT.pull = digitalio.Pull.UP