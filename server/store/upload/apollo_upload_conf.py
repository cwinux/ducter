#-*- encoding: utf-8 -*-

from optparse import OptionParser
import os
import re
import string
import sys
from datetime import datetime
import traceback
import hashlib
import socket
import random

class ApolloRsyncConf(object):
    """ 实现文件的rsync。
    #  Attributes:
    """
    def __init__(self, user, app, svr, pool, version, directory, reupload):
        """ 初始化
        """
        # rsync ip addr. 锁定域名的ip地址
        rsync_ip = socket.gethostbyname("rsync.sre.lecloud.com")
        # 文件上传的rsync 地址
        self.rsync_server="apollo@%s::upload" % (rsync_ip)
        # 上传的用户
        self.user = user
        # 上传配置的产品名
        self.app = app
        # 上传配置的服务名
        self.svr = svr
        # 上传配置的服务池名
        self.pool = pool
        # 上传配置的版本
        self.version = version
        # 上传配置所在的目录，必须是全路径
        self.directory = directory
        # 上传时间
        self.uptime = datetime.now().strftime('%Y-%m-%d %H:%M:%S')
        # 配置包的md5
        self.md5 = ""
        # 配置加密的动态key
        self.passwd = ''.join(random.sample(string.ascii_letters + string.digits, 32)) + \
                      ''.join(random.sample(string.ascii_letters + string.digits, 32)) + \
                      ''.join(random.sample(string.ascii_letters + string.digits, 32)) + \
                      ''.join(random.sample(string.ascii_letters + string.digits, 32))
        self.conf_file = "conf_%s_%s_%s_%s.tar.gz" % (app, svr, pool, version)
        self.conf_pathfile = "/tmp/%s" % (self.conf_file)
        self.conf_info_file = "conf_%s_%s_%s_%s.info" % (app, svr, pool, version)
        self.conf_info_pathfile = "/tmp/%s" % (self.conf_info_file)
        self.reupload = reupload
        
    def rsync(self):
        """ rsync pkg
        """
        cmd = "cd %s && tar -czvf - * | openssl des3 -salt -k %s -out %s" % \
            (self.directory, self.passwd, self.conf_pathfile)
        print "tar config path, cmd:%s" % (cmd)
        ret =os.system(cmd)
        if 0 <> ret:
            print "Failed to exec:%s" % (cmd)
            return False
        self.__md5()
        upload_conf_file = "conf_%s_%s_%s_%s.%s.tar.gz" % (self.app, self.svr, self.pool, self.version, self.md5[0:16])
        conf_info = "[conf]\ntype=conf\nreupload=%s\nuser=%s\napp=%s\nsvr=%s\npool=%s\nversion=%s\ndirectory=%s\npkg_file=%s\ntime=%s\nmd5=%s\npasswd=%s\n" % \
            (self.reupload, self.user, self.app, self.svr, self.pool, self.version, self.directory, upload_conf_file, self.uptime, self.md5, self.passwd)
        # write pkg_info
        print "Write conf info to:%s" % (self.conf_info_pathfile)
        with open(self.conf_info_pathfile, "wb") as f:
            f.write(conf_info)
        cmd = "rsync -atz %s %s/%s" % (self.conf_info_pathfile, self.rsync_server, self.conf_info_file)
        print "rsync pkg info file:%s, cmd:%s" % (self.conf_info_pathfile, cmd)
        ret =os.system(cmd)
        if 0 <> ret:
            print "Failed to exec:%s" % (cmd)
            return False
        cmd = "rsync -atz %s %s/%s" % (self.conf_pathfile, self.rsync_server, upload_conf_file)
        print "rsync config, cmd:%s" % (cmd)
        ret =os.system(cmd)
        if 0 <> ret:
            print "Failed to exec:%s" % (cmd)
            return False
        return True

    def __md5(self):
        """ pkg file's md5
        """
        md5_obj = hashlib.md5()
        with open(self.conf_pathfile, "rb") as f:
            while True:
                d = f.read(8096)
                if not d: break
                md5_obj.update(d)
        self.md5 = md5_obj.hexdigest().lower()

def main():
    """
      Running rsync upload pkg.
    """
    opt = OptionParser(usage="  %prog [options]\n\t Version:1.0(2017-11-13 15:30:00)")
    opt.add_option("-u","--username",action="store",type="string",dest="user",
                   help="apollo's username.")
    opt.add_option("-a","--app", action="store", type="string", dest="app",
                   help="apollo's product name.")
    opt.add_option("-s", "--svr", action="store", type="string", dest="svr",
                   help="apollo's service name.")
    opt.add_option("-p", "--pool", action="store", type="string", dest="pool",
                   help="apollo's service pool name.")
    opt.add_option("-v", "--version", action="store", type="string", dest="version",
                   help="service pool config's version.")
    opt.add_option("-d", "--directory", action="store", type="string", dest="directory",
                   help="service pool config's directory,it must be absolute path.")
    opt.add_option("-r", "--reupload", action="store_true", default="False", dest="reupload",
                   help="re-upload the pkg, default is false.")
    (options,args)=opt.parse_args()
    if not options.user or not len(options.user):
        print "Must specify the apollo user by -u, exit."
        exit(1)
    if not options.app or not len(options.app):
        print "Must specify the apollo product name by -a, exit."
        exit(1)
    if not options.svr or not len(options.svr):
        print "Must specify the apollo sevice name by -s, exit."
        exit(1)
    if not options.pool or not len(options.pool):
        print "Must specify the apollo sevice pool name by -p, exit."
        exit(1)
    if not options.version or not len(options.version):
        print "Must specify the service's version by -v, exit."
        exit(1)
    if not options.directory or not len(options.directory):
        print "Must specify the service pool config's directory by -d, exit."
        exit(1)
    if not os.path.isdir(options.directory):
        print "Service pool config's directory[%s] doesn't exist, exit." % (options.directory)
        exit(1)
    if not options.directory.startswith("/"):
        print "Service config's directory[%s] isn't absolute path, exit." % (options.directory)
        exit(1)
    print "Begin to rsync, user:%s, product:%s, svr:%s, pool:%s, version:%s, directory:%s, reupload=%s" % \
        (options.user, options.app, options.svr, options.pool, options.version, options.directory, options.reupload)
    try:
        rsync = ApolloRsyncConf(options.user, options.app, options.svr, options.pool, options.version, options.directory, options.reupload)
        if not rsync.rsync():
            exit(1)
    except Exception, e:
        print "Failed to upload, exception:%s" % (e)
        traceback.print_exc()
        exit(1)
    print "Success to rysnc."
    exit(0)

if __name__ == "__main__":
    main()

