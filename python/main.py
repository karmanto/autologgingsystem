import config
import time 
from time import sleep

config.init_rpi_gpio()

while True:
#    time.sleep(.5)
#    config.RPI_OUTPUT_ARRAY[1].value = True
#    time.sleep(.5)
#    config.RPI_OUTPUT_ARRAY[1].value = False
    input_values = [INPUT_PIN.value for INPUT_PIN in config.RPI_INPUT_ARRAY]
    print(f"\rInput pin values: {input_values}", end='', flush=True)
    time.sleep(1)
