import time 
from time import sleep
import json
import datetime
import os
from dotenv import load_dotenv

load_dotenv()

if os.getenv('FAKE_CONFIG', 'false').lower() == 'true':
    import fake_config as config
else:
    import config

config.init_rpi_gpio()
MCP1_INIT, MCP1_PINS = config.init_mcp(0x20)
MCP2_INIT, MCP2_PINS = config.init_mcp(0x21)
MCP3_INIT, MCP3_PINS = config.init_mcp(0x22)
config.RPI_OUTPUT_ARRAY[0].value = True
TIME_DELAY_INPUTS = [time.time()] * 66
RUNSECOND_INPUTS = [0] * 66
TIMEON_INPUTS = [0] * 66
INPUTS = [0] * 66
TIME_REC = 0
PREV_TIME = 0
CURR_TIME = 0

DB_INIT, DB = config.get_db_connection()
while not DB_INIT:
    time.sleep(5)
    DB_INIT, DB = config.get_db_connection()

CURSOR = DB.cursor()
CURSOR.execute ("SELECT * FROM data_monitor ORDER BY id DESC LIMIT 1")
ROW_MONITOR = CURSOR.fetchone()
CURSOR.execute	(	"UPDATE data_monitor SET cbc1=(%s), cbc2=(%s), prs1=(%s), prs2=(%s), prs3=(%s), prs4=(%s), prs5=(%s), prs6=(%s), prs7=(%s), prs8=(%s), dtr1=(%s), dtr2=(%s), dtr3=(%s), dtr4=(%s), dtr5=(%s), dtr6=(%s), dtr7=(%s), dtr8=(%s), " +
                    "spr0=(%s), spr1=(%s), spr2=(%s), spr3=(%s), spr4=(%s), spr5=(%s), spr6=(%s), spr7=(%s), spr8=(%s), spr9=(%s), " +
                    "spr10=(%s), spr11=(%s), spr12=(%s), spr13=(%s), spr14=(%s), spr15=(%s), spr16=(%s), spr17=(%s), " +
                    "spr18=(%s), spr19=(%s), spr20=(%s), spr21=(%s), spr22=(%s), spr23=(%s), spr24=(%s), spr25=(%s), " +
                    "spr26=(%s), spr27=(%s), spr28=(%s), spr29=(%s), spr30=(%s), spr31=(%s), spr32=(%s), spr33=(%s), " +
                    "spr34=(%s), spr35=(%s), spr36=(%s), spr37=(%s), spr38=(%s), spr39=(%s), spr40=(%s), spr41=(%s), " +
                    "spr42=(%s), spr43=(%s), spr44=(%s), spr45=(%s) WHERE id =(%s)", 
                (0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,ROW_MONITOR[0])
            )
DB.commit()
CURSOR.execute ("SELECT * FROM data_runtime LIMIT 1")
ROW_RUNTIME = CURSOR.fetchone()
for index, runsecond in enumerate(ROW_RUNTIME):
    if index >= 2:
        RUNSECOND_INPUTS[index - 2] = runsecond

def update_inputs(MCP_INIT, MCP_PINS, current_time, addNumber):
    if MCP_INIT:
        for index, INPUT_PIN in enumerate(MCP_PINS):
            if INPUT_PIN.value == False:
                if current_time - TIME_DELAY_INPUTS[index + addNumber] < 5:
                    continue
                else:
                    INPUTS[index + addNumber] = 1
                    if TIMEON_INPUTS[index + addNumber] != 0:
                        RUNSECOND_INPUTS[index + addNumber] += current_time - TIMEON_INPUTS[index + addNumber]

                    TIMEON_INPUTS[index + addNumber] = current_time
            else:
                INPUTS[index + addNumber] = 0
                TIME_DELAY_INPUTS[index + addNumber] = current_time
                if TIMEON_INPUTS[index + addNumber] != 0:
                    RUNSECOND_INPUTS[index + addNumber] += current_time - TIMEON_INPUTS[index + addNumber]

                TIMEON_INPUTS[index + addNumber] = 0

while True:
    time.sleep(.5)
    config.RPI_OUTPUT_ARRAY[0].value = not config.RPI_OUTPUT_ARRAY[0].value
    CURR_TIME = time.time()
    if CURR_TIME != PREV_TIME:
        PREV_TIME = CURR_TIME
        INPUTS = [0] * 66
        update_inputs(True, config.RPI_INPUT_ARRAY, 0)
        update_inputs(MCP1_INIT, MCP1_PINS, CURR_TIME, 18)
        update_inputs(MCP2_INIT, MCP2_PINS, CURR_TIME, 34)
        update_inputs(MCP3_INIT, MCP3_PINS, CURR_TIME, 50)

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

        CURSOR.execute	(	"UPDATE data_runtime SET cbc1=(%s), cbc2=(%s), prs1=(%s), prs2=(%s), prs3=(%s), prs4=(%s), prs5=(%s), prs6=(%s), prs7=(%s), prs8=(%s), dtr1=(%s), dtr2=(%s), dtr3=(%s), dtr4=(%s), dtr5=(%s), dtr6=(%s), dtr7=(%s), dtr8=(%s), " +
                            "spr0=(%s), spr1=(%s), spr2=(%s), spr3=(%s), spr4=(%s), spr5=(%s), spr6=(%s), spr7=(%s), spr8=(%s), spr9=(%s), " +
                            "spr10=(%s), spr11=(%s), spr12=(%s), spr13=(%s), spr14=(%s), spr15=(%s), spr16=(%s), spr17=(%s), " +
                            "spr18=(%s), spr19=(%s), spr20=(%s), spr21=(%s), spr22=(%s), spr23=(%s), spr24=(%s), spr25=(%s), " +
                            "spr26=(%s), spr27=(%s), spr28=(%s), spr29=(%s), spr30=(%s), spr31=(%s), spr32=(%s), spr33=(%s), " +
                            "spr34=(%s), spr35=(%s), spr36=(%s), spr37=(%s), spr38=(%s), spr39=(%s), spr40=(%s), spr41=(%s), " +
                            "spr42=(%s), spr43=(%s), spr44=(%s), spr45=(%s) WHERE id=1", 
                        (
                        RUNSECOND_INPUTS[0], RUNSECOND_INPUTS[1], RUNSECOND_INPUTS[2], RUNSECOND_INPUTS[3], RUNSECOND_INPUTS[4], RUNSECOND_INPUTS[5], RUNSECOND_INPUTS[6], RUNSECOND_INPUTS[7], RUNSECOND_INPUTS[8], RUNSECOND_INPUTS[9],
                        RUNSECOND_INPUTS[10], RUNSECOND_INPUTS[11], RUNSECOND_INPUTS[12], RUNSECOND_INPUTS[13], RUNSECOND_INPUTS[14], RUNSECOND_INPUTS[15], RUNSECOND_INPUTS[16], RUNSECOND_INPUTS[17], RUNSECOND_INPUTS[18], RUNSECOND_INPUTS[19],
                        RUNSECOND_INPUTS[20], RUNSECOND_INPUTS[21], RUNSECOND_INPUTS[22], RUNSECOND_INPUTS[23], RUNSECOND_INPUTS[24], RUNSECOND_INPUTS[25], RUNSECOND_INPUTS[26], RUNSECOND_INPUTS[27], RUNSECOND_INPUTS[28], RUNSECOND_INPUTS[29],
                        RUNSECOND_INPUTS[30], RUNSECOND_INPUTS[31], RUNSECOND_INPUTS[32], RUNSECOND_INPUTS[33], RUNSECOND_INPUTS[34], RUNSECOND_INPUTS[35], RUNSECOND_INPUTS[36], RUNSECOND_INPUTS[37], RUNSECOND_INPUTS[38], RUNSECOND_INPUTS[39],
                        RUNSECOND_INPUTS[40], RUNSECOND_INPUTS[41], RUNSECOND_INPUTS[42], RUNSECOND_INPUTS[43], RUNSECOND_INPUTS[44], RUNSECOND_INPUTS[45], RUNSECOND_INPUTS[46], RUNSECOND_INPUTS[47], RUNSECOND_INPUTS[48], RUNSECOND_INPUTS[49],
                        RUNSECOND_INPUTS[50], RUNSECOND_INPUTS[51], RUNSECOND_INPUTS[52], RUNSECOND_INPUTS[53], RUNSECOND_INPUTS[54], RUNSECOND_INPUTS[55], RUNSECOND_INPUTS[56], RUNSECOND_INPUTS[57], RUNSECOND_INPUTS[58], RUNSECOND_INPUTS[59],
                        RUNSECOND_INPUTS[60], RUNSECOND_INPUTS[61], RUNSECOND_INPUTS[62], RUNSECOND_INPUTS[63]
                        )
                    )
        DB.commit()

        try:
            with open('./storage/app/monitor.json', 'r') as monitor_file:
                monitor_data = json.load(monitor_file)

            now = datetime.datetime.now()
            monitor_data['time_updated'] = now.strftime("%d/%m/%Y %H:%M:%S")
            monitor_names = config.FIELD_STRING.split(',')
            for idx, item in enumerate(monitor_data['monitor_list']):
                name = item['name']
                if name in monitor_names:
                    input_index = monitor_names.index(name)
                    item['stat'] = bool(INPUTS[input_index])
                    item['runseconds'] = int(RUNSECOND_INPUTS[input_index])

            with open('./storage/app/monitor.json', 'w') as monitor_file:
                json.dump(monitor_data, monitor_file, indent=4)

        except FileNotFoundError:
            now = datetime.datetime.now()
            monitor_data = {
                "time_updated": now.strftime("%d/%m/%Y %H:%M:%S"),
                "monitor_list": [{"name": name, "stat": False, "runseconds": 0} for name in config.FIELD_STRING.split(",")]
            }
            with open('./storage/app/monitor.json', 'w') as monitor_file:
                json.dump(monitor_data, monitor_file, indent=4)
        except Exception as e:
            pass
