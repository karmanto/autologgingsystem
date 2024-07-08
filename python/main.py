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
    INPUTS = [False] * 66
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

    CURSOR.execute	(	"INSERT INTO data_monitor (" + config.FIELD_STRING + ") " +
					"VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s)", 
                    (
                    INPUTS[0], INPUTS[1], INPUTS[2], INPUTS[3], INPUTS[4], INPUTS[5], INPUTS[6], INPUTS[7], INPUTS[8], INPUTS[9], 
                    INPUTS[10], INPUTS[11], INPUTS[12], INPUTS[13], INPUTS[14], INPUTS[15], INPUTS[16], INPUTS[17], INPUTS[18], INPUTS[19], 
                    INPUTS[20], INPUTS[21], INPUTS[22], INPUTS[23], INPUTS[24], INPUTS[25], INPUTS[26], INPUTS[27], INPUTS[28], INPUTS[29], 
                    INPUTS[30], INPUTS[31], INPUTS[32], INPUTS[33], INPUTS[34], INPUTS[35], INPUTS[36], INPUTS[37], INPUTS[38], INPUTS[39], 
                    INPUTS[40], INPUTS[41], INPUTS[42], INPUTS[43], INPUTS[44], INPUTS[45], INPUTS[46], INPUTS[47], INPUTS[48], INPUTS[49], 
                    INPUTS[50], INPUTS[51], INPUTS[52], INPUTS[53], INPUTS[54], INPUTS[55], INPUTS[56], INPUTS[57], INPUTS[58], INPUTS[59], 
                    INPUTS[60], INPUTS[61], INPUTS[62], INPUTS[63]
                    )
                )
    DB.commit()	
