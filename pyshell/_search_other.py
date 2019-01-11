#-*- coding:utf-8 -*-
__author__ = "MuT6 Sch01aR"

import paramiko
import sys

def get_other(index,data):
    ssh = paramiko.SSHClient() # 创建SSH对象
    ssh.set_missing_host_key_policy(paramiko.AutoAddPolicy()) # 允许连接不在know_hosts文件中的主机
    ssh.connect(hostname='140.143.146.104', port=22, username='ubuntu', password='mfeng19931004') # 连接服务器

    stdin, stdout, stderr = ssh.exec_command('python3 /home/ubuntu/mengf/ontv/search.py '+index+' '+data) # 执行命令并获取命令结果
    #stdin为输入的命令
    #stdout为命令返回的结果
    #stderr为命令错误时返回的结果
    res,err = stdout.read(),stderr.read()
    result = res if res else err
    print(result.decode())
    ssh.close()#关闭连接