# coding:utf-8

import global_data
from threading import *
import sys
import random
import urllib.request
import json
import time
from pymysql import *
import urllib.parse


class GetData(object):
    def __init__(self, ip_adress, uuid):

        headers = random.choice(global_data.get_headers())
        try:
            request = urllib.request.Request(global_data.get_ip_url() + ip_adress, headers=headers)
            data = urllib.request.urlopen(request,timeout=3).read().decode('utf-8')
            json_str = json.loads(data)
            addr_city = str(json_str['content']['address_detail']['city'])
            addr_pri = str(json_str['content']['address_detail']['province'])
            addr = str(json_str['address'])
        except:
            addr_city = 'get_data_error'
            addr_pri = 'get_data_error'
            addr = 'get_data_error'
        h_time = time.strftime('%H', time.localtime(time.time()))

        self.tag_word = []
        sql_head = 'SELECT `content` , count( 1 ) AS counts FROM `search` WHERE '
        sql_foot = ' and ((curdate() - interval 30 day) <= cast(`search`.`date` as date)) GROUP BY `content` ORDER BY counts DESC LIMIT 0,5'
        sql = ['`ip_ad_city` = "' + addr_city + '"',
               '`ip_ad_pri` = "' + addr_pri + '"',
               '`ip_ad` = "' + addr + '"',
              # '`ip` = "' + ip_adress + '"',
               '`date_time` = ' + h_time,
               '`ip_ad_city` = "' + addr_city + '" and `date_time` = ' + h_time,
               '`ip_ad_pri` = "' + addr_pri + '" and `date_time` = ' + h_time,
               '`ip_ad` = "' + addr + '" and `date_time` = ' + h_time,
               '`ip` = "' + ip_adress + '" and `date_time` = '+ h_time,
              # '`uuid` = ' + uuid,
               '1'
               ]
        sql_last = 'SELECT  `content` , `date` FROM `search` WHERE `uuid` = "' + uuid + '" ORDER BY `id` DESC LIMIT 0, 3'
        len_sql = len(sql) + 1
        thread_list = []
        for index in range(len_sql):
            if index+1 == len_sql:
                sql_t = sql_last
            else:
                sql_t = sql_head + sql[index] + sql_foot
            sql_thread = Thread(target=self.get_data, args=(sql_t,))
            thread_list.append(sql_thread)
        len_thread_list = len(thread_list)
        for index in range(len_thread_list):
            thread_list[index].start()
        for index in range(len_thread_list):
            thread_list[index].join()
        # print(str(set(self.tag_word)))
        # 去重
        # print(urllib.parse.quote(str(list(set(self.tag_word))).encode("utf-8")))
        # 不去重复
        print(urllib.parse.quote(str(list(self.tag_word)).encode("utf-8")))

    def get_data(self, sql):
        try:
            database_config = global_data.database_config()
            conn = connect(host=database_config['host'], port=database_config['port'],
                           user=database_config['user'],
                           password=database_config['password'], database=database_config['database'],
                           charset=database_config['charset'])
            cs = conn.cursor()

            cs.execute(sql)
            content_data = cs.fetchall()
            # print(urllib.parse.quote(str(content_data).encode('utf-8')))
            len_content = len(content_data)
            for index in range(len_content):
                # 随机生成推荐标签
                if random.choice([0,1]) == 1:
                    self.tag_word.append(content_data[index][0])

            cs.close()
            conn.close()

        except:
            pass


if __name__ == '__main__':
    ip_address = sys.argv[1]
    uuid = sys.argv[2]
    try:
        GetData(ip_address, uuid)
    except:
        pass
