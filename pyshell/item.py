# coding:utf-8
"""
实现视频资源播放链接的爬取
并且返回json格式数据
by:孟峰
date:2018-07-02
"""

import urllib.request  # 爬虫
import re  # 正则匹配
import json  # json转换
import global_data
import random
import sys  # 接收参数


class InfoSpider(object):
    def __init__(self, info_type, info_id):
        url = global_data.get_info_url(int(info_type)) + '?m=vod-detail-id-' + str(info_id) + '.html'
        headers = random.choice(global_data.get_headers())
        self.request = urllib.request.Request(url=url, headers=headers)

    def get_data(self):
        # 构造字典
        json_dict = {}
        json_dict_in = {}
        json_dict_list = []
        try:
            data = urllib.request.urlopen(self.request,timeout=3).read().decode()  # 读取页面数据 并转换成str类型
        except:
            data = ''
        # 与匹配相关的正则表达式
        regex_out = r'<h3>(.+?)</h3>[\s.]*<ul>([\s\S]+?)</ul>'
        regex_in = r'(\S*?)\$(.*?)\s'
        regex_sub = r'<.+?>'
        res_out = re.findall(regex_out, data)
        len_res = len(res_out)
        for index in range(len_res):
            res_in_str = re.sub(regex_sub,'',res_out[index][1])
            json_dict["type"] = res_out[index][0]
            json_dict["play_info"] = []
            res_in = re.findall(regex_in, res_in_str)
            len_res_in = len(res_in)
            for index_in in range(len_res_in):
                json_dict_in["name"] = res_in[index_in][0]
                json_dict_in["link"] = res_in[index_in][1]
                json_dict_in_copy = json_dict_in.copy()
                json_dict["play_info"].append(json_dict_in_copy)
            json_dict_out_copy = json_dict.copy()
            json_dict_list.append(json_dict_out_copy)
            # print(json_dict)

        return json.dumps(json_dict_list)


def main(info_type = 0, info_id = 0):
    info_spider = InfoSpider(info_type, info_id)
    print(info_spider.get_data())


if __name__ == '__main__':
    info_type = sys.argv[1]
    info_id = sys.argv[2]
    main(info_type, info_id)
