import serial
port = serial.Serial("/dev/ttyUSB0", baudrate=115200, timeout=5.0)
def write(temp, wiatr, wilg):
    data_str = "%.2f%.2f%.2f" % (temp, wiatr, wilg)
    port.write("start" + data_str)
