#-*- encoding: utf-8 -*-
from optparse import OptionParser
import logging
import logging.handlers
import os
import sys
import fcntl 
import string
import time
import traceback
import shutil
import ConfigParser
import apollo_conf as conf
import apollo_db as db
reload(sys)
sys.setdefaultencoding('utf-8')

class ImportUpload(object):

    def __init__(self, host):
        """
        # 初始化 
        """
        # 本机的ip地址
        self.host = host
        # 数据库的handler
        self.db = db.DbHandler()
        # 所有上传的conf 的info文件列表
        self.conf_info_files = []
        # 所有上传的pkg的info的文件列表
        self.pkg_info_files = []
        # 所有的tar。gz文件饿列表
        self.tar_gz_files = {}
    
    def run(self):
        """
        # import上传的pkg、conf的文件 
        """
        try:
            self._loadNewUpload()
            self._importUploadPkgs()
            self._importUploadConfs()
            # 删除未被info文件引用的tar.gz文件
            for file in self.tar_gz_files:
                pathfile = os.path.join(conf.UPLOAD_PATH, file)
                conf.LOG.error("%s is unused, rm it." % (pathfile))
                self._rmFile(pathfile)
        except Exception, e:
            conf.LOG.error("Failed to deal new upload, Exception:%s" % (e) )
    def _loadNewUpload(self):
        """
        # 获取upload目录下的所有.info与tar.gz文件清单 
        """
        conf.LOG.info("Load all .conf and tar.gz files in directory:%s" % conf.UPLOAD_PATH)
        try:
            self.conf_info_files = []
            self.pkg_info_files = []
            self.tar_gz_files = {}
            files = os.listdir(conf.UPLOAD_PATH)
            for file in files:
                pathfile = os.path.join(conf.UPLOAD_PATH, file)
                if os.path.isdir(pathfile): #rm path
                    conf.LOG.info("%s is a directory, rm it" % pathfile)
                    self._rmFile(pathfile)
                    continue
                if file.startswith("conf_"):
                    if file.endswith(".info"):
                        conf.LOG.info("Find conf's info file:%s" % file)
                        self.conf_info_files.append(file)
                    elif file.endswith(".tar.gz"):
                        conf.LOG.info("Find conf's tar.gz file:%s" % file)
                        self.tar_gz_files[file] = True
                    else:
                        conf.LOG.error("%s is invalid conf file, rm it." % (pathfile))
                        self._rmFile(pathfile)
                elif file.startswith("pkg_"):
                    if file.endswith(".info"):
                        conf.LOG.info("Find pkg's info file:%s" % file)
                        self.pkg_info_files.append(file)
                    elif file.endswith(".tar.gz"):
                        conf.LOG.info("Find pkg's tar.gz file:%s" % file)
                        self.tar_gz_files[file] = False
                    else:
                        conf.LOG.error("%s is invalid pkg file, rm it." % (pathfile))
                        self._rmFile(pathfile)
                else:
                    if not file.startswith("."):
                      conf.LOG.error("%s is not pkg or conf file, rm it." % (pathfile))
                      self._rmFile(pathfile)
        except Exception, e:
            conf.LOG.error("Failed to load new upload, Exception:%s" % (e))

    def _importUploadPkgs(self):
        """
        # import 上传的pkg文件 
        """
        try:
            for file in self.pkg_info_files:
                pathfile = os.path.join(conf.UPLOAD_PATH, file)
                conf.LOG.info("Begin import pkg:%s" % pathfile)
                self._importUploadPkg(pathfile)
                conf.LOG.info("End import pkg:%s" % pathfile)
        except Exception, e:
            conf.LOG.error("Failed to accept upload-pkg, err:%s" % (e))
    
    def _importUploadConfs(self):
        """
        # import 上传的conf文件 
        """
        try:
            for file in self.conf_info_files:
                pathfile = os.path.join(conf.UPLOAD_PATH, file)
                conf.LOG.info("Begin import conf:%s" % pathfile)
                self._importUploadConf(pathfile)
                conf.LOG.info("End import conf:%s" % pathfile)
        except Exception, e:
            conf.LOG.error("Failed to accept upload-conf, err:%s" % (e))

    def _importUploadPkg(self, infoPathfile):
        """
        # import 上传的pkg文件 
        """
        try:
            conf.LOG.info("Start import upload pkg:%s" % (infoPathfile))
            info = self._parseUploadPkgInfo(infoPathfile)
            if not info:
                conf.LOG.error("Remove pkg info file for invalid, file:%s" % (infoPathfile))
                self._rmFile(infoPathfile)
                return
            if 32 <> len(info[conf.K_MD5]):
                conf.LOG.error("Remove pkg info file for md5[%s] invalid, file:%s" % (info[conf.K_MD5], infoPathfile))
                self._rmFile(infoPathfile)
                return
            if not self.tar_gz_files.has_key(info[conf.K_PKG_FILE]):#pkg 文件不存在
                if int(time.time() - os.path.getctime(infoPathfile)) > conf.PKG_MISS_MAX_SECOND:
                    conf.LOG.error("Remove pkg info file for miss pkg file, file:%s, second:%d" % (infoPathfile, conf.PKG_MISS_MAX_SECOND))
                    self._rmFile(infoPathfile)
                return
            # 删除tar.gz
            del self.tar_gz_files[info[conf.K_PKG_FILE]]
            # 将数据，插入到数据库
            app_id= self.db.get_app_id(info[conf.K_APP])
            svr_id = None
            if not app_id:
                info[conf.K_ERRMSG] = "App doesn't exist."
                conf.LOG.error("Failed to import pkg:%s, [%s]'s app doesn't exist" % (infoPathfile, info[conf.K_APP]))
            else:
                info[conf.K_APP_ID] = app_id
                svr_id = self.db.get_svr_id(app_id, info[conf.K_SVR])
                if not svr_id:
                    info[conf.K_ERRMSG] = "service doesn't exist."
                else:
                    info[conf.K_SVR_ID] = svr_id
            if not svr_id:
                self.db.insert_app_pkg_err(info, self.host)
                self._rmFile(infoPathfile)
                pkgfile=os.path.join(conf.UPLOAD_PATH, info[conf.K_PKG_FILE])
                self._rmFile(pkgfile)
                conf.LOG.error("Failed to import pkg:%s, [%s:%s]'s service doesn't exist" % (infoPathfile, info[conf.K_APP], info[conf.K_SVR]))
            else:
                dst_file = conf.PKG_FILE_FORMAT % (info[conf.K_APP], info[conf.K_SVR], info[conf.K_VERSION], info[conf.K_MD5][0:16])
                src_pkg = os.path.join(conf.UPLOAD_PATH, info[conf.K_PKG_FILE])
                dst_pkg = os.path.join(conf.ACCEPT_PATH, dst_file)
                info[conf.K_PKG_FILE] = dst_file
                self.db.insert_app_pkg_upload(info, self.host)
                self._rmFile(infoPathfile)
                shutil.move(src_pkg, dst_pkg)
                conf.LOG.info("Success to import pkg:%s, copy to:%s" % (infoPathfile, dst_pkg))
        except Exception, e:
            conf.LOG.error("Failed to import pkg, info file:%s, err:%s" % (infoPathfile, e))

    def _importUploadConf(self, infoPathfile):
        """
        # import 上传的conf文件 
        """
        try:
            conf.LOG.info("Start import upload conf:%s" % (infoPathfile))
            info = self._parseUploadConfInfo(infoPathfile)
            if not info:
                conf.LOG.error("Remove conf info file for invalid, file:%s" % (infoPathfile))
                self._rmFile(infoPathfile)
                return
            if 32 <> len(info[conf.K_MD5]):
                conf.LOG.error("Remove conf info file for md5[%s] invalid, file:%s" % (info[conf.K_MD5], infoPathfile))
                self._rmFile(infoPathfile)
                return
            if not self.tar_gz_files.has_key(info[conf.K_PKG_FILE]):#pkg 文件不存在
                if int(time.time() - os.path.getctime(infoPathfile)) > conf.PKG_MISS_MAX_SECOND:
                    conf.LOG.error("Remove conf info file for miss pkg file, file:%s, second:%d" % (infoPathfile, conf.PKG_MISS_MAX_SECOND))
                    self._rmFile(infoPathfile)
                return
            # 删除tar.gz
            del self.tar_gz_files[info[conf.K_PKG_FILE]]
            # 将数据，插入到数据库
            app_id= self.db.get_app_id(info[conf.K_APP])
            svr_id = None
            pool_id = None
            if not app_id:
                info[conf.K_ERRMSG] = "App doesn't exist."
                conf.LOG.error("Failed to import conf:%s, [%s]'s app doesn't exist" % (infoPathfile, info[conf.K_APP]))
            else:
                info[conf.K_APP_ID] = app_id
                svr_id = self.db.get_svr_id(app_id, info[conf.K_SVR])
                if not svr_id:
                    info[conf.K_ERRMSG] = "service doesn't exist."
                    conf.LOG.error("Failed to import conf:%s, [%s:%s]'s service doesn't exist" % (infoPathfile, info[conf.K_APP], info[conf.K_SVR]))
                else:
                    info[conf.K_SVR_ID] = svr_id
                    pool_id = self.db.get_pool_id(svr_id, info[conf.K_POOL])
                    if not pool_id:
                        info[conf.K_ERRMSG] = "service pool doesn't exist."
                        conf.LOG.error("Failed to import conf:%s, [%s:%s:%s]'s pool doesn't exist" % (infoPathfile, info[conf.K_APP], info[conf.K_SVR], info[conf.K_POOL]))
                    else:
                        info[conf.K_POOL_ID] = pool_id
            if not pool_id:
                self.db.insert_app_conf_err(info, self.host)
                self._rmFile(infoPathfile)
                pkgfile=os.path.join(conf.UPLOAD_PATH, info[K_PKG_FILE])
                self._rmFile(pkgfile)
            else:
                dst_file = conf.CONF_FILE_FORMAT % (info[conf.K_APP], info[conf.K_SVR], info[conf.K_POOL], info[conf.K_VERSION], info[conf.K_MD5][0:16])
                src_pkg = os.path.join(conf.UPLOAD_PATH, info[conf.K_PKG_FILE])
                dst_pkg = os.path.join(conf.ACCEPT_PATH, dst_file)
                info[conf.K_PKG_FILE] = dst_file
                self.db.insert_app_conf_upload(info, self.host)
                self._rmFile(infoPathfile)
                shutil.move(src_pkg, dst_pkg)
                conf.LOG.info("Success to import conf:%s" % (infoPathfile))
        except Exception, e:
            conf.LOG.error("Failed to import pkg, info file:%s, err:%s" % (infoPathfile, e))

    def _parseUploadPkgInfo(self, pathfile):
        """
        # parse pkg's info文件 
        """
        parser = ConfigParser.ConfigParser()
        info = {}
        try:
            parser.read(pathfile)
            info[conf.K_TYPE] = parser.get("pkg", conf.K_TYPE)
            info[conf.K_REUPLOAD] = parser.getboolean("pkg", conf.K_REUPLOAD)
            info[conf.K_USER] = parser.get("pkg", conf.K_USER)
            info[conf.K_APP] = parser.get("pkg", conf.K_APP)
            info[conf.K_SVR] = parser.get("pkg", conf.K_SVR)
            info[conf.K_VERSION] = parser.get("pkg", conf.K_VERSION)
            info[conf.K_DIRECTORY] = parser.get("pkg", conf.K_DIRECTORY)
            info[conf.K_PKG_FILE] = parser.get("pkg", conf.K_PKG_FILE)
            info[conf.K_TIME] = parser.get("pkg", conf.K_TIME)
            info[conf.K_MD5] = parser.get("pkg", conf.K_MD5)
            info[conf.K_PASSWD] = parser.get("pkg", conf.K_PASSWD)
        except Exception, e:
            conf.LOG.error("Failed to parse pkg info file:%s, err:%s" % (pathfile, e))
            return None
        return info
    def _parseUploadConfInfo(self, pathfile):
        """
        # parse conf's info文件 
        """
        parser = ConfigParser.ConfigParser()
        info = {}
        try:
            parser.read(pathfile)
            info[conf.K_TYPE] = parser.get("conf", conf.K_TYPE)
            info[conf.K_REUPLOAD] = parser.getboolean("conf", conf.K_REUPLOAD)
            info[conf.K_USER] = parser.get("conf", conf.K_USER)
            info[conf.K_APP] = parser.get("conf", conf.K_APP)
            info[conf.K_SVR] = parser.get("conf", conf.K_SVR)
            info[conf.K_POOL] = parser.get("conf", conf.K_POOL)
            info[conf.K_VERSION] = parser.get("conf", conf.K_VERSION)
            info[conf.K_DIRECTORY] = parser.get("conf", conf.K_DIRECTORY)
            info[conf.K_PKG_FILE] = parser.get("conf", conf.K_PKG_FILE)
            info[conf.K_TIME] = parser.get("conf", conf.K_TIME)
            info[conf.K_MD5] = parser.get("conf", conf.K_MD5)
            info[conf.K_PASSWD] = parser.get("conf", conf.K_PASSWD)
        except Exception, e:
            conf.LOG.error("Failed to parse conf info file:%s, err:%s" % (pathfile, e))
            return None
        return info

    def _rmFile(self, file):
        """
        # 删除文件或者目录
        """
        try:
            if os.path.isfile(file):
                os.remove(file)
            elif os.path.isdir(file):
                shutil.rmtree(file)
        except Exception, e:
            conf.LOG.error("Failed to rm file:%s, err:%s." % (file, e))

def init_logger(log_file):
    """ Initialize logger
       Arguement:
         logfile: log file's name
    """
    handler = logging.handlers.RotatingFileHandler(log_file,
                                        maxBytes = conf.LOG_FILE_SIZE,
                                        backupCount = conf.LOG_FILE_NUM)
    fmt = '[%(asctime)s] [%(levelname)s] [ %(filename)s:‘\
          ’%(lineno)s - %(name)s ] %(message)s '
    formatter = logging.Formatter(fmt)
    handler.setFormatter(formatter)
    logger = logging.getLogger('mylogger')
    logger.addHandler(handler)
    logger.setLevel(logging.DEBUG)
    return logger

def lock_proc_file(file):
    try:
      fcntl.flock(file.fileno(), fcntl.LOCK_EX|fcntl.LOCK_NB) 
      file.write(str(os.getpid()))  
    except Exception, e:
        conf.LOG.error("Failed to lock proc_file, err:%s." % (e))
        return False
    return True

def main():
    """
    # main 函数
    """
    opt = OptionParser()
    opt.add_option("-H","--host",action="store",type="string",dest="host",
                   help="upload host ip addr.")
    (options,args)=opt.parse_args()
    if not options.host or not len(options.host):
        print "Must specify the upload host by -h, exit."
        exit(1)
    if not os.path.isdir(conf.LOG_PATH):
        os.makedirs(conf.LOG_PATH)
    if not os.path.isdir(conf.ACCEPT_PATH):
        os.makedirs(conf.ACCEPT_PATH)
    log_file = os.path.join(conf.LOG_PATH, conf.IMPORT_LOG_FILE_NAME)
    conf.LOG = init_logger(log_file)
    lock_file = os.path.join(conf.LOG_PATH, conf.IMPORT_LOG_FILE_NAME + ".pid")
    if not os.path.exists(lock_file):  
      file = open(lock_file, "w")
      file.close()
    file = open(lock_file, "w")
    if not lock_proc_file(file):
      conf.LOG.info("Import upload process is running, exist.")
      file.close()
      return
    imp = ImportUpload(options.host)
    while True:
      time.sleep(2)
      conf.LOG.info("Start Import upload checking......")
      imp.run()
      conf.LOG.info("End Import upload check.")
    file.close()

if __name__ == "__main__":
    # do the UNIX double-fork magic, see Stevens' "Advanced
    # Programming in the UNIX Environment" for details (ISBN 0201563177)
    try:
        pid = os.fork()
        if pid > 0:
            # exit first parent
            sys.exit(0)
    except OSError, e:
        print >>sys.stderr, "Failed to fork child process: %d (%s)" % (e.errno, e.strerror)
        sys.exit(1)
    # do second fork
    try:
        pid = os.fork()
        if pid > 0:
            # exit from second parent, print eventual PID before
            print "Daemon pid %d" % pid
            sys.exit(0)
    except OSError, e:
        print >>sys.stderr, "Failed to fork daemon process: %d (%s)" % (e.errno, e.strerror)
        sys.exit(1)
    # start the daemon main loop
    main()