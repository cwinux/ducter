#-*- encoding: utf-8 -*-
from optparse import OptionParser
import logging
import logging.handlers
import os
import sys
import string
import fcntl
import time
import traceback
import shutil
import ConfigParser
import apollo_conf as conf
import apollo_db as db
reload(sys)
sys.setdefaultencoding('utf-8')

class SyncUpload(object):
    def __init__(self, host):
        """
        # 初始化
        """
        # 本机的IP地址，只同步非本机上传的pkg与conf
        self.host =host
        # 数据库handler
        self.db = db.DbHandler()
        # 保持通过点的数据文件
        self.sync_process_file = os.path.join(conf.ACCEPT_PATH, conf.SYNC_PROCESS_FILE_NAME)
        # 当前同步的位置点
        self.sync_id = 0
    
    def run(self):
        """
        # 执行同步
        """
        try:
            # 获取当前的同步点
            self._read_sync_id()
            # 获取需要同步的文件
            uploads = self.db.get_sync_upload(str(self.sync_id), self.host)
            for upload in uploads:
                conf.LOG.info("Begin sync file, type:%s, pkg:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
                if upload[conf.K_TYPE] == conf.UPLOAD_TYPE_PKG:
                    self._rsync_pkg(upload)
                elif upload[conf.K_TYPE] == conf.UPLOAD_TYPE_CONF:
                    self._rsync_conf(upload)
                else:
                    self.db.failed_sync(self.host, upload, "Unknown upload type:%s" % upload[conf.K_TYPE])
                self.sync_id = int(upload[conf.K_ID])
                conf.LOG.info("End sync file, pos:%s, type:%s, pkg:%s" % (str(self.sync_id), upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
        except Exception, e:
            conf.LOG.error("Failed to sync upload, Exception:%s" % (e) )
        self._save_sync_id()

    def _rsync_pkg(self, upload):
        """
        # sync pkg 文件
        """
        errmsg = ""
        conf.LOG.info("Begin sync pkg file, pkg:%s" % (upload[conf.K_PKG_FILE]))
        try:
            pkg_path = os.path.join(conf.REPO_PATH,upload[conf.K_APP],upload[conf.K_SVR],"pkg")
            if not os.path.isdir(pkg_path):
                os.makedirs(pkg_path)
            pkg_pathfile = os.path.join(pkg_path,upload[conf.K_PKG_FILE])
            cmd = "scp %s:%s  %s" % (upload[conf.K_UPLOAD_HOST], pkg_pathfile, pkg_pathfile)
            conf.LOG.info("Exce scp to sync, cmd:%s" % (cmd))
            ret = os.system(cmd)
            if not ret: return
            errmsg = "Failed to sync pkg, cmd:%s, retcode:%s" % (cmd, str(ret))
        except Exception, e:
            conf.LOG.error("Failed to sync pkg upload, Exception:%s" % (e) )
            errmsg = str(e)
        self.db.failed_sync(self.host, upload, errmsg)

    def _rsync_conf(self, upload):
        """
        # sync conf 文件
        """
        conf.LOG.info("Begin sync conf file, pkg:%s" % (upload[conf.K_PKG_FILE]))
        errmsg = ""
        try:
            conf_path = os.path.join(conf.REPO_PATH,upload[conf.K_APP],upload[conf.K_SVR],"conf",upload[conf.K_POOL])
            if not os.path.isdir(conf_path):
                os.makedirs(conf_path)
            conf_pathfile = os.path.join(conf_path,upload[conf.K_PKG_FILE])
            cmd = "scp %s:%s  %s" % (upload[conf.K_UPLOAD_HOST], conf_pathfile, conf_pathfile)
            conf.LOG.info("Exce scp to sync, cmd:%s" % (cmd))
            ret = os.system(cmd)
            if not ret: return
            errmsg = "Failed to sync conf upload, cmd:%s, retcode:%s" % (cmd, str(ret))
        except Exception, e:
            conf.LOG.error("Failed to sync conf upload, Exception:%s" % (e) )
            errmsg = str(e)
        self.db.failed_sync(self.host, upload, errmsg)

    def _save_sync_id(self):
        """
        # 保持同步点
        """
        conf.LOG.info("Save sync pos:%s to file:%s" % (str(self.sync_id), self.sync_process_file))
        with open(self.sync_process_file, "wb") as fd:
            fd.write(str(self.sync_id))
        
    def _read_sync_id(self):
        """
        # 加载同步点
        """
        self.sync_id = 0
        if not os.path.exists(self.sync_process_file):
            return
        with open(self.sync_process_file, "r") as fd:
            seq = fd.readline()
            seq.strip('\r\n ')
            if seq.isdigit():
                self.sync_id = int(seq)
        conf.LOG.info("Load sync pos:%s from file:%s" % (str(self.sync_id), self.sync_process_file))

    def _rmFile(self, file):
        """
        # 删除指定的文件或目录
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
                   help="sync host ip addr.")
    (options,args)=opt.parse_args()
    if not options.host or not len(options.host):
        print "Must specify the sync host by -h, exit."
        exit(1)
    if not os.path.isdir(conf.LOG_PATH):
        os.makedirs(conf.LOG_PATH)
    log_file = os.path.join(conf.LOG_PATH, conf.SYNC_LOG_FILE_NAME)
    conf.LOG = init_logger(log_file)
    lock_file = os.path.join(conf.LOG_PATH, conf.SYNC_LOG_FILE_NAME + ".pid")
    if not os.path.exists(lock_file):  
      file = open(lock_file, "w")
      file.close()
    file = open(lock_file, "w")
    if not lock_proc_file(file):
      conf.LOG.info("Sync upload process is running, exist.")
      file.close()
      return
    sync = SyncUpload(options.host)
    while True:
      time.sleep(2)
      conf.LOG.info("Start sync process checking ......")
      sync.run()
      conf.LOG.info("End sync process checking.")
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