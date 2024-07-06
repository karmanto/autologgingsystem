import config
import time 
from time import sleep

config.init_rpi_gpio()
config.RPI_OUTPUT_ARRAY[1].value = False
config.RPI_OUTPUT_ARRAY[2].value = False

while True:
    time.sleep(.5)
    config.RPI_OUTPUT_ARRAY[0].value = True
    time.sleep(.5)
    config.RPI_OUTPUT_ARRAY[0].value = False