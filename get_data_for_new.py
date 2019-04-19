# -*- coding: utf8 -*-
from lxml import etree
import requests, threading
import datetime
import math
import xml.etree.ElementTree as ET
import gzip
from io import BytesIO
from decimal import getcontext, Decimal
import numpy as np
import time
import socket
from dbconnect_for_new import connectDB

#要抓的時間區段
EndDate = '20180228'
StartDate = '20180201'
end_date = ''
minute_time = ''


def parseXML(tree, temp):

    while True:
        try:
            for infos in tree:
                for info in infos:
                    undivided_total_speed = 0
                    undivide_total_laneoccupy = 0
                    undivided_total_volume = 0

                    for lane in info:
                        now_speed = int(lane.attrib["speed"])
                        now_laneoccupy = int(lane.attrib["laneoccupy"])

                        for cars in lane:
                            undivided_total_speed += now_speed * int(cars.attrib["volume"])
                            undivide_total_laneoccupy += now_laneoccupy * int(cars.attrib["volume"])
                            undivided_total_volume += int(cars.attrib["volume"])

                    if undivided_total_volume != 0:
                        undivided_total_speed = Decimal(undivided_total_speed / undivided_total_volume).quantize(
                            Decimal('0.00'))
                        undivide_total_laneoccupy = Decimal(
                            (undivide_total_laneoccupy / undivided_total_volume) / 1.21).quantize(
                            Decimal('0.00'))

                    temp.setdefault(info.attrib["vdid"], []).append(
                        tuple([info.attrib["vdid"], info.attrib["datacollecttime"],
                               str(undivided_total_speed), str(undivide_total_laneoccupy),
                               str(undivided_total_volume * 60)]))

        except Exception as e:
            print(e)
            print('----------------------------------------------------------------------------' + end_date + " " + minute_time)
            continue
        break
    return temp


def UpAndInsert(x, createmonth, temp):
    print(temp)
    for i in temp:
        result = x.query_table_for_show()
        # print(result)
        t = str(createmonth) + "-" + str(i)
        if t not in result:
            x.create(t)
        else:
            x.getcode(t)
        data = temp.get(str(i))
        data = str(data)[1:-1]
        # print(i)
        x.insert_undivide(data)
    print('--------success insert--------')


def TimeToSearch():

    global EndDate, StartDate, end_date, minute_time

    StartDate = datetime.datetime.strptime(StartDate, "%Y%m%d")
    EndDate = datetime.datetime.strptime(EndDate, "%Y%m%d")
    substract_time_day = EndDate - StartDate + datetime.timedelta(1)
    total_days = math.floor((substract_time_day.total_seconds() / 86400))

    # 處理小時
    EndTime = datetime.datetime.strptime('2359', "%H%M")
    StartTime = datetime.datetime.strptime('0000', "%H%M")
    substract_time = EndTime - StartTime + datetime.timedelta(minutes=1)
    total_minutes = math.floor((substract_time.total_seconds() / 60))

    return total_days, total_minutes, StartTime


def main():

    x = connectDB()
    time_data = TimeToSearch()

    for day in range(0, time_data[0]):
        temp = {}
        count = 0
        end_date = StartDate + datetime.timedelta(day)
        createmonth = end_date.strftime("%Y-%m")
        end_date = end_date.strftime("%Y%m%d")
        print(end_date)
        for minute in range(0, time_data[1]):
            print(minute)
            minutemen = time_data[2] + datetime.timedelta(minutes=minute)
            minutemen = minutemen.strftime("%H%M")
            while True:
                try:
                    headers = {'user-agent': '"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36'}
                    result = requests.get("http://tisvcloud.freeway.gov.tw/history/vd/" + str(end_date) + "/vd_value_" + str(minutemen) + ".xml.gz", headers=headers)
                    result.encoding = 'utf8'
                    # jsonData +=\
                    sitemap = gzip.GzipFile(fileobj=BytesIO(result.content))
                    root = ET.parse(sitemap)
                    tree = root.getroot()
                    temp = parseXML(tree, temp)
                    result.close()
                except Exception as e:
                    if count < 5:
                        count += 1
                        print(e)
                        print('----------------------------------------------------------------------------' + end_date + " " + minutemen)
                        continue
                    else:
                        count = 0
                        break
                break
        print(temp)
        UpAndInsert(x, createmonth, temp)
        time.sleep(1)

    x.exit()


if __name__ == "__main__":
    main()