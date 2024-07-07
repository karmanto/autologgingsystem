import config
import time 
from time import sleep

config.init_rpi_gpio()
MCP1_INIT, MCP1_PINS = config.init_mcp(0x20)
MCP2_INIT, MCP2_PINS = config.init_mcp(0x21)
MCP3_INIT, MCP3_PINS = config.init_mcp(0x22)
config.RPI_OUTPUT_ARRAY[0].value = True
DB_INIT, DB = config.get_db_connection()

while not DB_INIT:
    time.sleep(5)
    DB_INIT, DB = config.get_db_connection()

CURSOR = DB.cursor()

while True:
    time.sleep(1)
    INPUTS = [True] * 66
    config.RPI_OUTPUT_ARRAY[0].value = not config.RPI_OUTPUT_ARRAY[0].value
    for index, INPUT_PIN in enumerate(config.RPI_INPUT_ARRAY):
        INPUTS[index] = not INPUT_PIN.value

    if MCP1_INIT:
        for index, INPUT_PIN in enumerate(MCP1_PINS):
            INPUTS[index + 18] = not INPUT_PIN.value

    if MCP2_INIT:
        for index, INPUT_PIN in enumerate(MCP2_PINS):
            INPUTS[index + 18 + 16] = not INPUT_PIN.value

    if MCP3_INIT:
        for index, INPUT_PIN in enumerate(MCP3_PINS):
            INPUTS[index + 18 + 16 + 16] = not INPUT_PIN.value

    # Create placeholders for the query
    placeholders = ','.join(['%s'] * 45)
    fields = ','.join(config.FIELD_TABLE_ARRAY[:45])

    CURSOR.execute(
        f"INSERT INTO data_monitor ({fields}) VALUES ({placeholders})",
        tuple(INPUTS[:45])
    )
    DB.commit()
