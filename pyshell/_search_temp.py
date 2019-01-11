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
import _search_other



request = urllib.request.Request(url='http://zuida.me/index.php?m=vod-search', data="西")

_search_other.get_other('http://zuida.me/index.php?m=vod-search',"西")
