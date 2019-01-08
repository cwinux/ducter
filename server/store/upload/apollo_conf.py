#-*- encoding: utf-8 -*-

#log的目录
LOG_PATH = "/letv/apollo/log"
#上传代码的pkg与配置的目录
UPLOAD_PATH = "/letv/upload"
#等待接收确认的pkg与配置的目录
ACCEPT_PATH = "/data/apollo/accept"
#rsync的根目录
REPO_PATH="/data/apollo/repo"
#import cron脚本的log文件
IMPORT_LOG_FILE_NAME = "import_upload.log"
#accept cron脚本的log文件
ACCEPT_LOG_FILE_NAME = "accept_upload.log"
#sync cron脚本的log文件
SYNC_LOG_FILE_NAME = "sync_upload.log"
#sync的同步位置文件
SYNC_PROCESS_FILE_NAME = "sync_process.dat"
#log文件的大小
LOG_FILE_SIZE = 4096 * 1024  # 日志文件的大小
#log循环文件的数量
LOG_FILE_NUM  = 5  # 循环日志的文件数量
#pgk gz文件的格式为pkg_[产品]_[服务]_[版本]_[md5_16].tar.gz
PKG_FILE_FORMAT = "pkg_%s_%s_%s_%s.tar.gz"
#配置 gz文件的格式为pkg_[产品]_[服务]_[服务池]_[版本]_[md5_16].tar.gz
CONF_FILE_FORMAT = "conf_%s_%s_%s_%s_%s.tar.gz"

#下面是key的定义
K_ID = "id"
K_TYPE="type"
K_REUPLOAD="reupload"
K_USER="user"
K_APP="app"
K_APP_ID="app_id"
K_SVR="svr"
K_SVR_ID="svr_id"
K_POOL="pool"
K_POOL_ID="pool_id"
K_VERSION="version"
K_DIRECTORY="directory"
K_TIME="time"
K_MD5="md5"
K_PASSWD="passwd"
K_PKG_FILE="pkg_file"
K_ERRMSG = "errmsg"
K_STATE="state"
K_UPLOAD_HOST="upload_host"

#上传pkg或配置的接收状态
ACCEPT_STATE_INIT = "0"
ACCEPT_STATE_ACCEPT="1"
ACCEPT_STATE_REJECT="2"
ACCEPT_STATE_EXCEPTION="3"

#上传pkg软件包
UPLOAD_TYPE_PKG = "pkg"
#上传配置包
UPLOAD_TYPE_CONF = "conf"
#pkg的文件缺失的最大时间，单位为s
PKG_MISS_MAX_SECOND = 1800
#logger对象
LOG=None
#数据库的连接信息
DB_SERVER = "m3204i.bjxy.db.lecloud.com"
DB_USER = "dcmdAdmin"
DB_PASSWORD = "dcmdAdmin@OPS"
DB_PORT = 3204
DB_NAME = "dcmd_apollo"
