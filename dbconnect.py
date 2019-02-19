from datetime import date, datetime, timedelta
import pymysql.cursors
import numpy as np

# 连接配置信息
config = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'db': 'one_year_undivide',
    'charset': 'utf8',
    'cursorclass': pymysql.cursors.DictCursor,
}



class connectDB():

    now_time = ''

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
        self.cursor.execute("SET NAMES utf8mb4")
        self.cursor.execute("SET CHARACTER SET utf8mb4")
        self.cursor.execute("SET character_set_connection = utf8mb4")


    def create(self,end_date):

        global now_time
        self.cursor.execute("SET NAMES utf8mb4;")
        self.cursor.execute("DROP TABLE IF EXISTS undivide_%s"%(end_date))
        sql = """CREATE TABLE undivide_%s (
                        Number INT NOT NULL AUTO_INCREMENT,
                        vdid  VARCHAR(191),
                        datacollecttime VARCHAR(191),
                        speed FLOAT,
                        laneoccupy FLOAT,
                        volume INT,
                         PRIMARY KEY (Number))"""%(end_date)
        self.cursor.execute(sql)

        sql = """ALTER TABLE `undivide_%s` CHANGE `vdid` `vdid` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci """%(end_date)
        self.cursor.execute(sql)
        sql = """ALTER TABLE `undivide_%s` CHANGE `datacollecttime` `datacollecttime` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL""" % (end_date)
        self.cursor.execute(sql)
        # sql = """ALTER TABLE `divide_%s` CHANGE `vsrid` `vsrid` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci """ % (end_date)
        # self.cursor.execute(sql)
        now_time = "undivide_" + end_date

    def insert_divide(self, temp):
        global now_time
        sql = "INSERT INTO %s(vdid, datacollecttime, speed, laneoccupy, volume) VALUES"%(now_time) + temp
        # val = temp
        try:
            # print(vdid, datacollecttime, vsrid, speed, laneoccupy, volume)
            self.connection.ping(reconnect=True)
            self.cursor.execute(sql)
            self.connection.commit()
        except:
            self.connect()
            self.connection.rollback()


    def insert_undivide(self, vdid, datacollecttime, speed, laneoccupy, volume):
        datacollecttime = datetime.strptime(datacollecttime, "%Y/%m/%d %H:%M:%S")

    def exit(self):
        self.connection.close()

