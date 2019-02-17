from datetime import date, datetime, timedelta
import pymysql.cursors
import numpy as np

# 连接配置信息
config = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'db': 'oneyear',
    'charset': 'utf8',
    'cursorclass': pymysql.cursors.DictCursor,
}

class connectDB():


    def __init__(self):
        self.cursor = None
        self.connection = None
        self.connect()

    # create connection
    def connect(self):
        self.connection = pymysql.connect(**config)
        # check connection
        if(self.connection) :
            print("^___<")
            self.cursor = self.connection.cursor()

    # def create(self):
    #     self.cursor.execute("DROP TABLE IF EXISTS divide")
    #     sql = """CREATE TABLE divide (
    #              vdid  CHAR(50) ,
    #              datacollecttime  DATETIME,
    #              vsrid CHAR(5),
    #              speed FLOAT,
    #              laneoccupy FLOAT,
    #              volume INT )"""
    #     self.cursor.execute(sql)

    def insert_divide(self, temp):
        print(temp)
        # datacollecttime = datetime.strptime(datacollecttime, "%Y/%m/%d %H:%M:%S")
        # # print(datacollecttime)
        sql = "INSERT INTO divide (vdid, datacollecttime, vsrid, speed, laneoccupy, volume) VALUES" + temp
        # val = temp
        try:
            # print(vdid, datacollecttime, vsrid, speed, laneoccupy, volume)
            self.connection.ping(reconnect=True)
            self.cursor.execute(sql)
            self.connection.commit()
        except:
            self.connection.rollback()


    def insert_undivide(self, vdid, datacollecttime, speed, laneoccupy, volume):
        datacollecttime = datetime.strptime(datacollecttime, "%Y/%m/%d %H:%M:%S")

    def exit(self):
        self.connection.close()

