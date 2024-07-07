import config
import time 
from time import sleep

config.init_rpi_gpio()
MCP1_INIT, MCP1_PINS = config.init_mcp(0x20)
MCP2_INIT, MCP2_PINS = config.init_mcp(0x21)
MCP3_INIT, MCP3_PINS = config.init_mcp(0x22)
DB_INIT, DB = config.get_db_connection()

while True:
   time.sleep(1)
   INPUTS = [True] * 66
   config.RPI_OUTPUT_ARRAY[0].value = not config.RPI_OUTPUT_ARRAY[0].value
   for index, INPUT_PIN in enumerate(config.RPI_INPUT_ARRAY):
    INPUTS[index] = INPUT_PIN.value
    
    if MCP1_INIT :
        for index, INPUT_PIN in enumerate(MCP1_PINS):
            INPUTS[index + 18] = INPUT_PIN.value

    if MCP2_INIT :
        for index, INPUT_PIN in enumerate(MCP2_PINS):
            INPUTS[index + 18 + 16] = INPUT_PIN.value

    if MCP3_INIT :
        for index, INPUT_PIN in enumerate(MCP3_PINS):
            INPUTS[index + 18 + 16 + 16] = INPUT_PIN.value
    
    print(f"\rInput pin values: {INPUTS}", end='', flush=True)

    #db = config.get_db_connection()
    #cursor = db.cursor()
    #cursor.execute("SELECT VERSION()")
    #result = cursor.fetchone()
    #print(f"Database version: {result[0]}")
    #cursor.close()
    #db.close()
