#-*- encoding: utf-8 -*-
import MySQLdb

import datetime
import traceback
import apollo_conf as conf

class DbHandler(object):
    """ 此为private oss的数据库操作对象。此对象raise数据库自身的exception
    #  Attributes:
    #    conn: mysql的数据库句柄
    """
    def __init__(self):
        self.conn = None

    def check_connection(self):
        self._check_connection()

    def create_cursor(self):
        return self.conn.cursor()

    def exec_sql(self, cursor, sql):
        """
        """
        cursor.execute(sql)
    
    def commit(self):
        self.conn.commit()
    
    def rollback(self):
        self.conn.rollback()
    
    def get_app_id(self, app_name):
        """
        #获取 app的app id
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql = r"select app_id from dcmd_app where app_name=%s "
            cursor.execute(sql, (MySQLdb.escape_string(app_name)))
            results = cursor.fetchall()
            for row in results:
                return row[0]
            return None
        except Exception, e:
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            raise e
        finally:
            self._close_cursor(cursor)

    def get_svr_id(self, app_id, svr_name):
        """
        #获取 服务的svr id
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql = r"select svr_id from dcmd_service where app_id=%s and svr_name=%s"
            cursor.execute(sql, (app_id, MySQLdb.escape_string(svr_name)))
            results = cursor.fetchall()
            for row in results:
                return row[0]
            return None
        except Exception, e:
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            raise e
        finally:
            self._close_cursor(cursor)

    def get_pool_id(self, svr_id, pool_name):
        """
        #获取 服务池的pool id
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql = r"select svr_pool_id from dcmd_service_pool where svr_id=%s and svr_pool=%s"
            cursor.execute(sql, (svr_id, MySQLdb.escape_string(pool_name)))
            results = cursor.fetchall()
            for row in results:
                return row[0]
            return None
        except Exception, e:
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            raise e
        finally:
            self._close_cursor(cursor)

    def insert_app_pkg_upload(self, info, host):
        """
        #往上传的pkg表中，插入上传的pkg记录
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql=r"insert into dcmd_app_pkg_upload (app_id, app_name, svr_id, svr_name,"\
                "svr_pool_id, svr_pool, upload_type, upload_username, accept_username,"\
                "version, src_path, pkg_file, md5, passwd, upload_host, is_reupload, is_accept,"\
                "upload_time, accept_time)"\
                "values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s,%s,%s,%s,%s,%s,now())"
            cursor.execute(sql, (info[conf.K_APP_ID], MySQLdb.escape_string(info[conf.K_APP]),info[conf.K_SVR_ID],MySQLdb.escape_string(info[conf.K_SVR]),
                "0","",conf.UPLOAD_TYPE_PKG,MySQLdb.escape_string(info[conf.K_USER]), "",
                MySQLdb.escape_string(info[conf.K_VERSION]), MySQLdb.escape_string(info[conf.K_DIRECTORY]),
                MySQLdb.escape_string(info[conf.K_PKG_FILE]), MySQLdb.escape_string(info[conf.K_MD5]),
                MySQLdb.escape_string(info[conf.K_PASSWD]), MySQLdb.escape_string(host), str(int(info[conf.K_REUPLOAD]==str(True))),
                conf.ACCEPT_STATE_INIT, info[conf.K_TIME]))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def insert_app_pkg_err(self, info, host):
        """
        #往上传err表中，插入无效的pkg上传记录
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql=r"insert into dcmd_app_upload_error(app_name, svr_name, svr_pool, upload_type, upload_username,"\
                "version, src_path, pkg_file, md5, passwd, upload_host, errmsg, is_reupload, upload_time)"\
                "values(%s,%s,%s,%s,%s,%s,%s,%s,%s, %s, %s,%s,%s,%s)"
            cursor.execute(sql, (MySQLdb.escape_string(info[conf.K_APP]),MySQLdb.escape_string(info[conf.K_SVR]),
                "",conf.UPLOAD_TYPE_PKG,MySQLdb.escape_string(info[conf.K_USER]), MySQLdb.escape_string(info[conf.K_VERSION]), MySQLdb.escape_string(info[conf.K_DIRECTORY]),
                MySQLdb.escape_string(info[conf.K_PKG_FILE]), MySQLdb.escape_string(info[conf.K_MD5]),
                MySQLdb.escape_string(info[conf.K_PASSWD]), MySQLdb.escape_string(host), MySQLdb.escape_string(info[conf.K_ERRMSG]),
                str(int(info[conf.K_REUPLOAD]==str(True))), info[conf.K_TIME]))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def insert_app_conf_upload(self, info, host):
        """
        #往上传pkg表中，插入上传的配置记录
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql=r"insert into dcmd_app_pkg_upload (app_id, app_name, svr_id, svr_name,"\
                "svr_pool_id, svr_pool, upload_type, upload_username, accept_username,"\
                "version, src_path, pkg_file, md5, passwd, upload_host, is_reupload, is_accept,"\
                "upload_time, accept_time)"\
                "values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,now())"
            cursor.execute(sql, (info[conf.K_APP_ID], MySQLdb.escape_string(info[conf.K_APP]),info[conf.K_SVR_ID],MySQLdb.escape_string(info[conf.K_SVR]),
                info[conf.K_POOL_ID], MySQLdb.escape_string(info[conf.K_POOL]),conf.UPLOAD_TYPE_CONF,MySQLdb.escape_string(info[conf.K_USER]), "",
                MySQLdb.escape_string(info[conf.K_VERSION]), MySQLdb.escape_string(info[conf.K_DIRECTORY]),
                MySQLdb.escape_string(info[conf.K_PKG_FILE]), MySQLdb.escape_string(info[conf.K_MD5]),
                MySQLdb.escape_string(info[conf.K_PASSWD]), MySQLdb.escape_string(host), str(int(info[conf.K_REUPLOAD]==str(True))),
                conf.ACCEPT_STATE_INIT, info[conf.K_TIME]))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def insert_app_conf_err(self, info, host):
        """
        #往上传err表中，插入上传的无效配置记录
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql=r"insert into dcmd_app_upload_error(app_name, svr_name, svr_pool, upload_type, upload_username,"\
                "version, src_path, pkg_file, md5, passwd, upload_host, errmsg, is_reupload, upload_time)"\
                "values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, %s)"
            cursor.execute(sql, (MySQLdb.escape_string(info[conf.K_APP]),MySQLdb.escape_string(info[conf.K_SVR]),
                "",conf.UPLOAD_TYPE_CONF,MySQLdb.escape_string(info[conf.K_USER]), MySQLdb.escape_string(info[conf.K_VERSION]), MySQLdb.escape_string(info[conf.K_DIRECTORY]),
                MySQLdb.escape_string(info[conf.K_PKG_FILE]), MySQLdb.escape_string(info[conf.K_MD5]),
                MySQLdb.escape_string(info[conf.K_PASSWD]), MySQLdb.escape_string(host), MySQLdb.escape_string(info[conf.K_ERRMSG]),
                str(int(info[conf.K_REUPLOAD]==str(True))), info[conf.K_TIME]))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def get_confirm_upload(self, host):
        """
        #获取host主机上传的，确认的上传pkg或配置记录
        """
        self._check_connection()
        uploads = []
        try:
            cursor = self.conn.cursor()
            sql = r"select id, app_id, app_name, svr_id, svr_name, svr_pool_id, svr_pool, "\
                "upload_type, accept_username, version, pkg_file, is_accept, md5 from dcmd_app_pkg_upload where is_accept<>0 and upload_host=%s "
            cursor.execute(sql, (MySQLdb.escape_string(host)))
            results = cursor.fetchall()
            for row in results:
                upload = {conf.K_ID:row[0], conf.K_APP_ID:row[1], conf.K_APP:row[2], conf.K_SVR_ID:row[3], conf.K_SVR:row[4],
                        conf.K_POOL_ID:row[5], conf.K_POOL:row[6], conf.K_TYPE:row[7], conf.K_USER:row[8],
                        conf.K_VERSION:row[9], conf.K_PKG_FILE:row[10], conf.K_STATE:row[11], conf.K_MD5:row[12]}
                uploads.append(upload)
            return uploads
        except Exception, e:
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            raise e
        finally:
            self._close_cursor(cursor)

    def accept_upload(self, id, is_pkg):
        """
        # 接受id指定的上传记录
        """
        self._check_connection()
        uploads = []
        try:
            cursor = self.conn.cursor()
            sql = r"insert into dcmd_app_upload_history(app_id,app_name,svr_id,svr_name,svr_pool_id,svr_pool,"\
                "upload_type,upload_username,accept_username,version,src_path,pkg_file,md5,passwd,upload_host,"\
                "is_reupload,is_accept,upload_time,accept_time) select app_id,app_name,svr_id,svr_name,svr_pool_id,svr_pool,"\
                "upload_type,upload_username,accept_username,version,src_path,pkg_file,md5,passwd,upload_host,"\
                "is_reupload,is_accept,upload_time,accept_time from dcmd_app_pkg_upload where id=%s"
            cursor.execute(sql, (id))
            if is_pkg:
                sql = r"delete from dcmd_app_pkg_version where (app_id, svr_id, version) in (select app_id, svr_id, version from dcmd_app_pkg_upload where id=%s)"
                cursor.execute(sql, (id))
                sql = r"insert into dcmd_app_pkg_version(app_id,svr_id,username,version,md5,passwd,ctime) "\
                    "select app_id, svr_id, accept_username, version, md5, passwd, now() from dcmd_app_pkg_upload where id=%s"
                cursor.execute(sql, (id))
            else:
                sql = r"delete from dcmd_app_conf_version where (app_id, svr_id, svr_pool_id, version) in (select app_id, svr_id, svr_pool_id, version from dcmd_app_pkg_upload where id=%s)"
                cursor.execute(sql, (id))
                sql = r"insert into dcmd_app_conf_version(app_id,svr_id,svr_pool_id,username,version,md5,passwd,ctime) "\
                    "select app_id, svr_id, svr_pool_id, accept_username, version, md5, passwd, now() from dcmd_app_pkg_upload where id=%s"
                cursor.execute(sql, (id))
            sql = r"delete from dcmd_app_pkg_upload where id=%s"
            cursor.execute(sql, (id))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def reject_upload(self, id, state, errmsg):
        """
        # 拒绝id指定的pkg或配置上传记录
        """
        self._check_connection()
        uploads = []
        try:
            cursor = self.conn.cursor()
            sql = r"insert into dcmd_app_reject_history(app_id,app_name,svr_id,svr_name,svr_pool_id,svr_pool,"\
                "upload_type,upload_username,accept_username,version,src_path,pkg_file,md5,passwd,upload_host,"\
                "is_reupload,is_accept,errmsg,upload_time,accept_time) select app_id,app_name,svr_id,svr_name,svr_pool_id,svr_pool,"\
                "upload_type,upload_username,accept_username,version,src_path,pkg_file,md5,passwd,upload_host,"\
                "is_reupload,%s,%s,upload_time,accept_time from dcmd_app_pkg_upload where id=%s"
            cursor.execute(sql, (state, MySQLdb.escape_string(errmsg), id))
            sql = r"delete from dcmd_app_pkg_upload where id=%s"
            cursor.execute(sql, (id))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)

    def get_sync_upload(self, seq, host):
        """
        # 获取host主机需要同步的上传记录
        """
        self._check_connection()
        uploads = []
        try:
            cursor = self.conn.cursor()
            sql = r"select id, app_name, svr_name, svr_pool, "\
                "upload_type, version, pkg_file, md5, upload_host "\
                "from dcmd_app_upload_history where id>%s and upload_host<>%s order by id"
            cursor.execute(sql, (seq, MySQLdb.escape_string(host)))
            results = cursor.fetchall()
            for row in results:
                upload = {conf.K_ID:row[0], conf.K_APP:row[1], conf.K_SVR:row[2],
                          conf.K_POOL:row[3], conf.K_TYPE:row[4], conf.K_VERSION:row[5],
                          conf.K_PKG_FILE:row[6], conf.K_MD5:row[7],conf.K_UPLOAD_HOST:row[8]}
                uploads.append(upload)
            return uploads
        except Exception, e:
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            raise e
        finally:
            self._close_cursor(cursor)

    def failed_sync(self, host, upload, errmsg):
        """
        # 记录host主机同步upload记录失败
        """
        self._check_connection()
        try:
            cursor = self.conn.cursor()
            sql = r"insert into dcmd_app_upload_sync_error(app_name,svr_name,svr_pool, "\
                "upload_type, version, pkg_file, md5, upload_host, sync_host, errmsg, sync_time) "\
                "values(%s,%s,%s,%s,%s,%s,%s,%s,%s,%s, now())"
            cursor.execute(sql, (MySQLdb.escape_string(upload[conf.K_APP]),MySQLdb.escape_string(upload[conf.K_SVR]),
                MySQLdb.escape_string(upload[conf.K_POOL]),MySQLdb.escape_string(upload[conf.K_TYPE]),MySQLdb.escape_string(upload[conf.K_VERSION]),
                MySQLdb.escape_string(upload[conf.K_PKG_FILE]),MySQLdb.escape_string(upload[conf.K_MD5]),MySQLdb.escape_string(upload[conf.K_UPLOAD_HOST]),
                MySQLdb.escape_string(host),MySQLdb.escape_string(errmsg)[0:255]))
            self.conn.commit()
        except Exception, e:
            traceback.print_exc()
            # catch exception的原因是为了rollback，目的是对外隐藏transaction
            self.conn.rollback()
            raise e
        finally:
            self._close_cursor(cursor)
        
    def _close_cursor(self, cursor):
        """ 强行关闭cursor而且不raise Exception
        """
        try:
            cursor.close()
        except:
            pass

    def _connect(self):
        """ 此方法无条件的重新连接。若先前的连接存在，则close后重新连接
        """
        if self.conn:
            # 不让close引起exception
            try:
                self.conn.close()
            except:
                pass
        self.conn=MySQLdb.connect(host = conf.DB_SERVER,
                                  user = conf.DB_USER,
                                  passwd = conf.DB_PASSWORD,
                                  port = conf.DB_PORT,
                                  db = conf.DB_NAME,
                                  charset = "utf8")
        self.conn.autocommit(False)

    def _check_connection(self):
        
        if self.conn is None:
            self._connect()
        else:
            try:
                self.conn.commit()
                self.conn.ping(True)
            except Exception, e:
                # 连接失败，重新连接
                self._connect()

