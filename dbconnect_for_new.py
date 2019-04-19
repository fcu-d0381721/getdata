from datetime import date, datetime, timedelta
import pymysql.cursors
import numpy as np

# 连接配置信息
config = {
    'host': '127.0.0.1',
    'port': 3306,
    'user': 'root',
    'password': '',
    'db': 'unDivide1',
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

    def query_table_for_show(self):
        table = []
        sql = "show TABLES"
        self.cursor.execute(sql)
        result = self.cursor.fetchall()
        for i in range(len(result)):
            # print(result[i].get('Tables_in_unDivide'))
            table.append(result[i].get('Tables_in_unDivide1'))
        return table

    def getcode(self, code):
        global now_time
        now_time = code

    def create(self, name):

        global now_time
        self.cursor.execute("SET NAMES utf8mb4;")

        sql = """CREATE TABLE `%s` (
                        Number INT NOT NULL AUTO_INCREMENT,
                        vdid  VARCHAR(191),
                        datacollecttime VARCHAR(191),
                        speed VARCHAR(191),
                        laneoccupy VARCHAR(191),
                        volume VARCHAR(191),
                         PRIMARY KEY (Number))"""%(name)
        self.cursor.execute(sql)

        sql = """ALTER TABLE `%s` CHANGE `vdid` `vdid` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci """%(name)
        self.cursor.execute(sql)
        sql = """ALTER TABLE `%s` CHANGE `datacollecttime` `datacollecttime` VARCHAR(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL""" % (name)
        self.cursor.execute(sql)

        now_time = name

    def insert_undivide(self, temp):
        global now_time

        # print(now_time)
        sql = """INSERT INTO `%s` (vdid, datacollecttime, speed, laneoccupy, volume) VALUES %s"""%(now_time, temp)
        # val = temp
        try:
            # print(temp)
            self.connection.ping(reconnect=True)
            self.cursor.execute(sql)
            self.connection.commit()
        except Exception as e:
            print(e)
            self.connect()
            self.connection.rollback()

    def exit(self):
        self.connection.close()

