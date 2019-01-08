#-*- encoding: utf-8 -*-
from optparse import OptionParser
import logging
import logging.handlers
import os
import fcntl
import sys
import string
import time
import traceback
import shutil
import ConfigParser
import apollo_conf as conf
import apollo_db as db
reload(sys)
sys.setdefaultencoding('utf-8')

class AcceptUpload(object):

    def __init__(self, host):
        """
        # 初始化
        """
        # 主机的ip地址，只接受本机的上传文件
        self.host = host
        # 数据库的handler
        self.db = db.DbHandler()
    
    def run(self):
        """
        # 执行确认上传的接受处理
        """
        try:
            accept_uploads = self.db.get_confirm_upload(self.host)
            for upload in accept_uploads:
                conf.LOG.info("Begin accept [%s], pkg:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
                if int(upload[conf.K_STATE]) == int(conf.ACCEPT_STATE_ACCEPT):
                    self._accept_upload(upload)
                elif int(upload[conf.K_STATE]) == int(conf.ACCEPT_STATE_REJECT):
                    self._reject_upload(upload, conf.ACCEPT_STATE_REJECT, "Reject")
                else:
                    self._reject_upload(upload, upload[conf.K_STATE], "Invalid state:%s" % upload[conf.K_STATE])
                conf.LOG.info("End accept [%s], pkg:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
        except Exception, e:
            conf.LOG.error("Failed to accept upload, Exception:%s" % (e) )
            traceback.print_exc()

    def _accept_upload(self, upload):
        """
        # 执行确认上传的接受处理
        """
        conf.LOG.info("Begin accept [%s], pkg:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
        try:
            is_pkg = upload[conf.K_TYPE]== conf.UPLOAD_TYPE_PKG
            if is_pkg:
                pkg_path = os.path.join(conf.REPO_PATH, upload[conf.K_APP], upload[conf.K_SVR], "pkg")
                if not os.path.isdir(pkg_path):
                    os.makedirs(pkg_path)
                #src file
                src_file = os.path.join(conf.ACCEPT_PATH, upload[conf.K_PKG_FILE])
                if os.path.isfile(src_file):
                    #dst file
                    dst_file = conf.PKG_FILE_FORMAT % (upload[conf.K_APP], upload[conf.K_SVR], upload[conf.K_VERSION], upload[conf.K_MD5][0:16])
                    dst_file = os.path.join(pkg_path, dst_file)
                    #mv file
                    if os.path.isfile(dst_file):
                        self._rmFile(dst_file)
                    shutil.move(src_file, dst_file)
                    #update db
                    self.db.accept_upload(upload[conf.K_ID], True)
                else:
                    self._reject_upload(upload, conf.ACCEPT_STATE_EXCEPTION, "pkg doesn't exist,pkg:%s" % src_file)
            else:
                pool_path=os.path.join(conf.REPO_PATH, upload[conf.K_APP], upload[conf.K_SVR], "conf", upload[conf.K_POOL])
                if not os.path.isdir(pool_path):
                    os.makedirs(pool_path)
                #src file
                src_file = os.path.join(conf.ACCEPT_PATH, upload[conf.K_PKG_FILE])
                if os.path.isfile(src_file):
                    #dst file
                    dst_file = conf.CONF_FILE_FORMAT % (upload[conf.K_APP], upload[conf.K_SVR], upload[conf.K_POOL], upload[conf.K_VERSION], upload[conf.K_MD5][0:16])
                    dst_file = os.path.join(pool_path, dst_file)
                    #mv
                    if os.path.isfile(dst_file):
                        self._rmFile(dst_file)
                    shutil.move(src_file, dst_file)
                    #update db
                    self.db.accept_upload(upload[conf.K_ID], False)
                else:
                    self._reject_upload(upload, conf.ACCEPT_STATE_EXCEPTION, "pkg doesn't exist,pkg:%s" % src_file)
            conf.LOG.info("End accept [%s], pkg:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE]))
            return
        except Exception, e:
            conf.LOG.error("Failed to accept upload, Exception:%s" % (e) )
            traceback.print_exc()
                
    def _reject_upload(self, upload, state, errmsg):
        conf.LOG.info("Reject import, type[%s], pkg:%s, err:%s" % (upload[conf.K_TYPE], upload[conf.K_PKG_FILE], errmsg))
        try:
            self.db.reject_upload(upload[conf.K_ID], state, errmsg)
            pkgFile = os.path.join(conf.ACCEPT_PATH, upload[conf.K_PKG_FILE])
            self._rmFile(pkgFile)
        except Exception, e:
            conf.LOG.error("Failed to reject upload, Exception:%s" % (e) )
            traceback.print_exc()

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
                   help="upload host ip addr.")
    (options,args)=opt.parse_args()
    if not options.host or not len(options.host):
        print "Must specify the upload host by -h, exit."
        exit(1)
    if not os.path.isdir(conf.LOG_PATH):
        os.makedirs(conf.LOG_PATH)
    log_file = os.path.join(conf.LOG_PATH, conf.ACCEPT_LOG_FILE_NAME)
    conf.LOG = init_logger(log_file)
    lock_file = os.path.join(conf.LOG_PATH, conf.ACCEPT_LOG_FILE_NAME + ".pid")
    if not os.path.exists(lock_file):  
      file = open(lock_file, "w")
      file.close()
    file = open(lock_file, "w")
    if not lock_proc_file(file):
      conf.LOG.info("Accept upload process is running, exist.")
      file.close()
      return
    accept = AcceptUpload(options.host)
    while True:
      time.sleep(2)
      conf.LOG.info("Start check new accept upload ......")
      accept.run()
      conf.LOG.info("End check new accept upload.")
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