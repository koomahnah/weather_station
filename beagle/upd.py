import sched, time
from datetime import datetime,timedelta
import mysql.connector

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

def compute_average():
    print "compute_average"
    query = ("SELECT time, temp, wiatr, wilg FROM dane2 "
             "WHERE time BETWEEN %s AND %s")
    tmp_dt = datetime.now() - timedelta(hours=1)
    start_dt = datetime(tmp_dt.year, tmp_dt.month, tmp_dt.day,
            tmp_dt.hour, 0, 0)
    stop_dt = datetime(tmp_dt.year, tmp_dt.month, tmp_dt.day,
            tmp_dt.hour, 59, 59)
    start = start_dt.strftime("%Y-%m-%d %H:%M:%S")
    stop = stop_dt.strftime("%Y-%m-%d %H:%M:%S")
    print "start is", start, "stop is", stop
    cursor.execute(query, (start, stop))
    print "received:"
    for (time, temp, wiatr, wilg) in cursor:
        print time, temp, wiatr, wilg

def update_event(unix_time):
    print "update_event"
    print "now is", datetime.now()
    add_entry = ("INSERT INTO dane2 "
                 "(time, temp, wiatr, wilg) "
                 "VALUES (%s, %s, %s, %s)")
    dt = datetime.fromtimestamp(unix_time)
#    if dt.minute == 0 and dt.second == 0:
#        compute_average()
    dt_str = dt.strftime("%Y-%m-%d %H:%M:%S")
    print "inserting:"
    print add_entry, (dt_str, "15", "20", "30")
    cursor.execute(add_entry, (dt_str, "15", "20", "30"))

cnx = mysql.connector.connect(user='cumana', password='',
        host='mysql.agh.edu.pl', database='cumana')
upd_sched = sched.scheduler(time.time, time.sleep)
cursor = cnx.cursor()
compute_average()
while True:
    sched_next_event(upd_sched, next_event_time())
cnx.close()
