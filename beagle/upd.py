import sched, time
from datetime import datetime,timedelta
import mysql.connector
import logging

def round_ten(x):
    return (x+10)//10*10

def next_event_time():
    now_dt = datetime.now()
    next_sec = round_ten(now_dt.second)
    next_min = now_dt.minute
    if next_sec == 60:
        next_sec = 0
        next_min += 1
    next_dt = datetime(now_dt.year, now_dt.month, now_dt.day,
            now_dt.hour, next_min, next_sec)
    return time.mktime(next_dt.timetuple())

def sched_next_event(sch, unix_time):
    sch.enterabs(unix_time, 1, update_event, argument=(unix_time,)) 
    sch.run()

def fill_averages():
    """ Fill tables containing averaged values of parameters. """
    logging.info("entry")
    time_dt = datetime.now() - timedelta(hours=1)
    start_dt = datetime(time_dt.year, time_dt.month, time_dt.day,
            time_dt.hour, 0, 0)
    stop_dt = datetime(time_dt.year, time_dt.month, time_dt.day,
            time_dt.hour, 59, 59)
    print compute_average(start_dt, stop_dt, ["wiatr", "temp", "wilg"], "dane2")

def compute_average(start_dt, stop_dt, param, table):
    """ Compute average of parameters' names list 'param' over time 'start_dt' -
        'stop_dt' (datetime object), using 'table' (string). """
    logging.info("entry")
    query = ("SELECT %s FROM %s "
             "WHERE time BETWEEN '%s' AND '%s'")
    start = start_dt.strftime("%Y-%m-%d %H:%M:%S")
    stop = stop_dt.strftime("%Y-%m-%d %H:%M:%S")
    param_str = ''
    for i, element in enumerate(param):
        if i != 0:
            param_str += ', '
        param_str += element
    query = query % (param_str, table, start, stop)
    logging.info("%r", query)
    cursor.execute(query, ())
    logging.info("received:")
    n = 0
    param_sum = [0] * len(param)
    for row in cursor:
        n += 1
        for i, element in enumerate(row):
            param_sum[i] += row[element]
        logging.info("%r", row)
    param_avg = {}
    for i, element in enumerate(param_sum):
        param_avg[param[i]] = float(param_sum[i]) / n

    return param_avg

def update_event(unix_time):
    logging.info("update_event")
    logging.info("now is %r", datetime.now())
    add_entry = ("INSERT INTO dane2 "
                 "(time, temp, wiatr, wilg) "
                 "VALUES (%s, %s, %s, %s)")
    dt = datetime.fromtimestamp(unix_time)
#    if dt.minute == 0 and dt.second == 0:
#       fill_averages()
    dt_str = dt.strftime("%Y-%m-%d %H:%M:%S")
    query = add_entry % (dt_str, "15", "25", "15")
    logging.info("inserting: %r", query)
    cursor.execute(add_entry, (dt_str, "15", "25", "15"))

logging.basicConfig(level=logging.INFO, format='%(levelname)s - %(funcName)s: %(message)s')
cnx = mysql.connector.connect(user='cumana', password='',
        host='mysql.agh.edu.pl', database='cumana')
upd_sched = sched.scheduler(time.time, time.sleep)
cursor = cnx.cursor(dictionary=True)
#fill_averages()
#while True:
#    continue
while True:
    sched_next_event(upd_sched, next_event_time())
cnx.close()
