import sched, time
from datetime import datetime,timedelta
import mysql.connector
import logging
import serial

def round_ten(x):
    return (x+10)//10*10

def next_event_time():
    now_dt = datetime.now()
    round_seconds = round_ten(now_dt.seconds) - now_dt.seconds
    next_dt = now_dt + timedelta(seconds=round_seconds)
    return time.mktime(next_dt.timetuple())

def next_event_time():
    now_dt = datetime.now()
    next_sec = round_ten(now_dt.second)
    next_min = now_dt.minute
    if next_sec == 60:
        next_sec = 0
        next_min += 1
    if next_min == 60:
        next_min = 0
    next_dt = datetime(now_dt.year, now_dt.month, now_dt.day,
            now_dt.hour, next_min, next_sec)
    return time.mktime(next_dt.timetuple())
def sched_next_event(sch, unix_time):
    sch.enterabs(unix_time, 1, update_event, argument=(unix_time,)) 
    sch.run()

def get_uart_data():
    logging.info("Getting data from uart")
    if port.read(5) != b"start":
        port.reset_input_buffer()
        return None
    temp = float(port.read(6))
    cisn = float(port.read(6))
    pyl = float(port.read(6))
    azot = float(port.read(6))
    wilg = float(port.read(6))
    return [temp, cisn, pyl, azot, wilg]

def update_event(unix_time):
    logging.info("update_event")
    logging.info("now is %r", datetime.now())
    add_entry = ("INSERT INTO dane "
                 "(time, temp, cisn, pyl, azot, wilg) "
                 "VALUES (%s, %s, %s, %s, %s, %s)")
    dt = datetime.fromtimestamp(unix_time)
    dt_str = dt.strftime("%Y-%m-%d %H:%M:%S")
    r = get_uart_data()
    if r == None:
        return
    logging.info("Got %r from uart", [r[0], r[1], r[2], r[3], r[4]])
    query = add_entry % (dt_str, r[0], r[1], r[2], r[3], r[4])
    logging.info("inserting: %r", query)
    cursor.execute(add_entry, (dt_str, r[0], r[1], r[2], r[3], r[4]))

logging.basicConfig(level=logging.INFO, format='%(levelname)s - %(funcName)s: %(message)s')
cnx = mysql.connector.connect(user='cumana', password='vuFij0BS',
        host='mysql.agh.edu.pl', database='cumana')
upd_sched = sched.scheduler(time.time, time.sleep)
cursor = cnx.cursor(dictionary=True)
port = serial.Serial("/dev/ttyS0", baudrate=115200, timeout=5.0)
port.reset_input_buffer()
while True:
    sched_next_event(upd_sched, next_event_time())
cnx.close()
