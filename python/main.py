import config
import time 
from time import sleep

#config.init_rpi_gpio()
#MCP1_INIT, MCP1_PINS = config.init_mcp(0x20)
#MCP2_INIT, MCP2_PINS = config.init_mcp(0x21)
#MCP3_INIT, MCP3_PINS = config.init_mcp(0x22)

#while True:
#    time.sleep(.5)
#    config.RPI_OUTPUT_ARRAY[1].value = True
#    time.sleep(.5)
#    config.RPI_OUTPUT_ARRAY[1].value = False
#    input_values = [INPUT_PIN.value for INPUT_PIN in config.RPI_INPUT_ARRAY]
#    print(f"\rInput pin values: {input_values}", end='', flush=True)
#    time.sleep(1)
#    if MCP1_INIT :
#        MCP_VALUES = [PIN.value for PIN in MCP1_PINS] 
#        print(f"\rMCP pin values: {MCP_VALUES}", end='', flush=True)

#    time.sleep(1)

db = config.get_db_connection()
cursor = db.cursor()
cursor.execute("SELECT VERSION()")
result = cursor.fetchone()
print(f"Database version: {result[0]}")
cursor.close()
db.close()
