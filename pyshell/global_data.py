# 资源站地址  / 注意
__url = ['http://zuida.me/','http://156zy.com/','http://kubozy.cc/','https://www.okzy.co/']

# ip接口
__ak = 'qXCng3GQtjFos5vKxZlokQI5njoI3LlQ'
__url_ip = 'http://api.map.baidu.com/location/ip?ak='+__ak+'&coor=bd09ll&ip='

#数据库配置
def database_config():
    return {'host':'localhost', 'port':3306, 'user':'root', 'password':'mfeng19931004', 'database':'video','charset':'utf8'}

def get_spider_url():
    # 获取搜索采集地址 列表
    __len_url = len(__url)
    __spider_url = []
    for index in range(__len_url):
        __spider_url.append(__url[index] + 'index.php?m=vod-search')
    return __spider_url

def get_info_url(type = 0):
    return __url[type]

def get_play_url():
    pass

def get_ip_url():
    return __url_ip


def get_headers():
    # 用户头
    __headers = [{'User-Agent': 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:23.0) Gecko/20100101 Firefox/23.0'},
                {'User-Agent': 'Opera/9.80(WindowsNT6.1;U;en)Presto/2.8.131Version/11.11'},
                {'User-Agent': 'Mozilla/5.0(Macintosh;IntelMacOSX10_7_0)AppleWebKit/535.11(KHTML,likeGecko)Chrome/17.0.963.56Safari/535.11'},
                {'User-Agent': 'Mozilla/5.0(iPhone;U;CPUiPhoneOS4_3_3likeMacOSX;en-us)AppleWebKit/533.17.9(KHTML,likeGecko)Version/5.0.2Mobile/8J2Safari/6533.18.5'},
                {'User-Agent': 'Mozilla/5.0(iPod;U;CPUiPhoneOS4_3_3likeMacOSX;en-us)AppleWebKit/533.17.9(KHTML,likeGecko)Version/5.0.2Mobile/8J2Safari/6533.18.5'},
                {'User-Agent': 'Mozilla/5.0(iPad;U;CPUOS4_3_3likeMacOSX;en-us)AppleWebKit/533.17.9(KHTML,likeGecko)Version/5.0.2Mobile/8J2Safari/6533.18.5'},
                {'User-Agent': 'Mozilla/5.0(Linux;U;Android2.3.7;en-us;NexusOneBuild/FRF91)AppleWebKit/533.1(KHTML,likeGecko)Version/4.0MobileSafari/533.1'},
                {'User-Agent': 'MQQBrowser/26Mozilla/5.0(Linux;U;Android2.3.7;zh-cn;MB200Build/GRJ22;CyanogenMod-7)AppleWebKit/533.1(KHTML,likeGecko)Version/4.0MobileSafari/533.1'},
                {'User-Agent': 'Opera/9.80(Android2.3.4;Linux;OperaMobi/build-1107180945;U;en-GB)Presto/2.8.149Version/11.10'},
                {'User-Agent': 'Mozilla/5.0(Linux;U;Android3.0;en-us;XoomBuild/HRI39)AppleWebKit/534.13(KHTML,likeGecko)Version/4.0Safari/534.13'},
                {'User-Agent': 'Mozilla/5.0(BlackBerry;U;BlackBerry9800;en)AppleWebKit/534.1+(KHTML,likeGecko)Version/6.0.0.337MobileSafari/534.1+'},
                {'User-Agent': 'Mozilla/5.0(hp-tablet;Linux;hpwOS/3.0.0;U;en-US)AppleWebKit/534.6(KHTML,likeGecko)wOSBrowser/233.70Safari/534.6TouchPad/1.0'},
                {'User-Agent': 'Mozilla/5.0(SymbianOS/9.4;Series60/5.0NokiaN97-1/20.0.019;Profile/MIDP-2.1Configuration/CLDC-1.1)AppleWebKit/525(KHTML,likeGecko)BrowserNG/7.1.18124'},
                {'User-Agent': 'Mozilla/5.0(compatible;MSIE9.0;WindowsPhoneOS7.5;Trident/5.0;IEMobile/9.0;HTC;Titan)'},
                {'User-Agent': 'UCWEB7.0.2.37/28/999'},
                {'User-Agent': 'NOKIA5700/UCWEB7.0.2.37/28/999'},
                {'User-Agent': 'Openwave/UCWEB7.0.2.37/28/999'}]
    return __headers


if __name__ == '__main__':
    print(get_spider_url())
    print(get_headers())
