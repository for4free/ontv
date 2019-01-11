# coding:utf-8
"""
实现视频资源的搜索功能
并且返回json格式数据
by:孟峰
date:2018-07-02
"""

import urllib.request  # request请求
import re  # 正则匹配
import json  # json转换
import random
import global_data
from threading import Thread  # 多线程爬取
import sys  # 接收参数
from pymysql import *
import time
import urllib.parse
# from threading import Lock  # 避免数据出错 暂且不用


class VideoSpider(object):
    def __init__(self, word, ipaddress, uuid):
        self.url = global_data.get_spider_url()
        self.headers = random.choice(global_data.get_headers())
        values = urllib.parse.urlencode({"wd": str(word), "submit": "search"}).encode('utf-8')
        self.url_len = len(self.url);
        self.request = []
        self.__json_list = []
        # 互斥锁
        # self.mutex = Lock()
        # 构造request对象列表
        for i in range(self.url_len):
            self.request.append(urllib.request.Request(url=self.url[i], data=values, headers=self.headers))
        # 开启线程执行数据库操作
        Thread(target=self.insert_ip_address, args=(word, ipaddress, uuid)).start()

    def insert_ip_address(self, word, ip_address, uuid):
        word = urllib.parse.unquote(word)
        try:
            # 准备要插入的时间
            now_time = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
            h_time = time.strftime('%H', time.localtime(time.time()))
            # 获取地址
            try:
                req = urllib.request.Request(global_data.get_ip_url() + ip_address, headers=self.headers)
                data = urllib.request.urlopen(req,timeout=3).read().decode()
                json_str = json.loads(data)
                addr_city = json_str['content']['address_detail']['city']
                addr_pri = json_str['content']['address_detail']['province']
                addr = str(json_str['address'])

            except:
                addr_city = 'get_data_error'
                addr_pri = 'get_data_error'
                addr = 'get_data_error'

            database_config = global_data.database_config()
            conn = connect(host=database_config['host'], port=database_config['port'], user=database_config['user'], password=database_config['password'], database=database_config['database'],
                           charset=database_config['charset'])
            cs = conn.cursor()
            sql = "INSERT INTO search (content,ip,ip_ad,ip_ad_pri,ip_ad_city,date,date_time,uuid) VALUES (%s,%s,%s,%s,%s,%s,%s,%s)"
            cs.execute(sql, [str(word), str(ip_address), str(addr), str(addr_pri[:10]), str(addr_city[:10]), str(now_time), str(h_time), str(uuid)])
            conn.commit()
            cs.close()
            conn.close()
        except Exception as e:
            #print(e)
            pass

    def get_video_data_list(self, index):
        # 构造字典
        __dict_data = {}
        try:
            __data = urllib.request.urlopen(self.request[index],timeout=3).read().decode()  # 读取页面数据 并转换成str类型

        except:
            __data = ''
        # 与匹配相关的正则表达式  搜索页面
        pattern = re.compile(r'<a href="/\?m=vod-detail-id-(\d+).html"\s*target="_blank">(.+?)</a>' \
                             r'</span>\s*<span class="xing_vb5">(.*?)</span>\s*<span class="xing_vb6">(\d+-\d+-\d+)</span>')
        res = re.findall(pattern, __data)  # 匹配并获取结果
        data_len = len(res)
        if data_len >= 1:  # 根据长度判断是都存在搜索结果
            for i in range(data_len):
                __dict_data["come_from"] = index
                __dict_data["id"] = res[i][0]
                __dict_data["name"] = res[i][1]
                __dict_data["version"] = res[i][2]
                __dict_data["time"] = res[i][3]
                __dict_copy = __dict_data.copy()  # 拷贝一下防止只append  引用而导致 结果只浅拷贝
                self.__json_list.append(__dict_copy)

    def get_video_data_json(self):
        # 构造字典
        __main_json = {}
        # 多线程获取
        __threads = []
        # 多线程发送request请求 并获取数据
        for index in range(self.url_len):
            __threads.append(Thread(target=self.get_video_data_list, args=(index,)))
        for index in range(self.url_len):
            __threads[index].start()
        for index in range(self.url_len):
            # 等待线程执行完成获取数据
            __threads[index].join()

        __data_str = self.__json_list

        for item in __data_str:
            if len(item) > 0:
                __main_json["ok"] = True
                __main_json["result"] = __data_str
                return json.dumps(__main_json)

        __result = {'ok': False, 'result': []}
        return json.dumps(__result)


def main(keyword, ipaddress, uuid):
    video_spider = VideoSpider(keyword, ipaddress, uuid)
    print(video_spider.get_video_data_json())


if __name__ == '__main__':
    keyworld = sys.argv[1]
    ipaddress = sys.argv[2]
    uuid = sys.argv[3]
    main(keyworld, ipaddress, uuid)
