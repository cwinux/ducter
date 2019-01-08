#include "dcmd_proxy_app.h"

#include <CwxDate.h>
#include <CwxFile.h>
#include <CwxMd5.h>

namespace dcmd {

  DcmdProxyApp::DcmdProxyApp() {
    data_buf_ = NULL;
    data_buf_len_ = 0;
    err_2k_[0] = 0x00;
  }

  DcmdProxyApp::~DcmdProxyApp() {
  }

  int DcmdProxyApp::init(int argc, char** argv) {
    string err_msg;
    // 首先调用架构的init api
    if (CwxAppFramework::init(argc, argv) == -1) return -1;
    // 检查是否通过-f指定了配置文件，若没有，则采用默认的配置文件
    if ((NULL == this->getConfFile()) || (strlen(this->getConfFile()) == 0))
      this->setConfFile("dcmd_proxy.conf");
    // 加载配置文件，若失败则退出
    if (0 != config_.init(getConfFile())) {
      CWX_ERROR((config_.err_msg()));
      return -1;
    }
    // 设置运行日志的输出level
    if (config_.conf().is_debug_){
      setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|
        CwxLogger::LEVEL_WARNING|CwxLogger::LEVEL_DEBUG);
    }else{
      setLogLevel(CwxLogger::LEVEL_ERROR|CwxLogger::LEVEL_INFO|
        CwxLogger::LEVEL_WARNING);
    }
    return 0;
  }

  int DcmdProxyApp::initRunEnv(){
    // 设置系统的时钟间隔，最小刻度为1ms，此为0.2s。
    this->setClick(100);//0.1s
    // 设置工作目录
    this->setWorkDir(config_.conf().work_home_.c_str());
    // 设置循环运行日志的数量
    this->setLogFileNum(config_.conf().log_file_num_);
    // 设置每个日志文件的大小
    this->setLogFileSize(config_.conf().log_file_msize_*1024*1024);
    // 调用架构的initRunEnv，使以上设置的参数生效
    if (CwxAppFramework::initRunEnv() == -1 ) return -1;
    // set version
    this->setAppVersion(kDcmdProxyVersion);
    // set last modify date
    this->setLastModifyDatetime(kDcmdProxyModifyDate);
    // set compile date
    this->setLastCompileDatetime(CWX_COMPILE_DATE(_BUILD_DATE));
    // 将加载的配置文件信息输出到日志文件中，以供查看检查
    config_.outputConfig();
    // block signal
    blockSignal(SIGCHLD);
    // 启动网络
    // 建立agent的listen
    CWX_INFO(("Open agent listen: %s:%u",
      config_.conf().listen_.getHostName().c_str(),
      config_.conf().listen_.getPort()));
    if (0 > this->noticeTcpListen(SVR_TYPE_AGENT,
      config_.conf().listen_.getHostName().c_str(),
      config_.conf().listen_.getPort(),
      false))
    {
      CWX_ERROR(("Can't register the agent listen: addr=%s, port=%d",
        config_.conf().listen_.getHostName().c_str(),
        config_.conf().listen_.getPort()));
      return -1;
    }
    return 0;
  }


  void DcmdProxyApp::onTime(CwxTimeValue const& current) {
    //uint32_t  now = current.sec();
    // 调用基类的onTime函数
    CwxAppFramework::onTime(current);
  }

  void DcmdProxyApp::onSignal(int signum){
    //  int status = 0;
    switch(signum){
    case SIGQUIT: 
      // 若监控进程通知退出，则推出
      CWX_INFO(("Recieve exit signal, exit right now."));
      this->stop();
      break;
    default:
      // 其他信号，全部忽略
      CWX_INFO(("Recieve signal=%d, ignore it.", signum));
      break;
    }
  }

  int DcmdProxyApp::onConnCreated(CwxAppHandler4Msg& conn, bool& bSuspendConn, bool& )
  {
    // 获取连接的ip
    char conn_ip[128];
    memset(conn_ip, 0x00, 128);
    conn.getRemoteAddr(conn_ip, 128);
    if (!conn_ip[0]) {
      CWX_ERROR(("Failure to get connection's remote ip, conn_id=%u.",
        conn.getConnInfo().getConnId()));
      return -1;
    }
    int ret = 0;
    if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) {
      if (config_.conf().allow_ips_.size()) {
        if (config_.conf().allow_ips_.find(string(conn_ip)) == config_.conf().allow_ips_.end()) {
          //check c net
          char const* ptr = strrchr(conn_ip, '.');
          if (!ptr) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
          conn_ip[ptr - conn_ip] = 0;
          if (config_.conf().allow_ips_.find(string(conn_ip)) == config_.conf().allow_ips_.end()) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
          // check b net
          ptr = strrchr(conn_ip, '.');
          if (!ptr) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
          conn_ip[ptr - conn_ip] = 0;
          if (config_.conf().allow_ips_.find(string(conn_ip)) == config_.conf().allow_ips_.end()) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
          // check a net
          ptr = strrchr(conn_ip, '.');
          if (!ptr) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
          conn_ip[ptr - conn_ip] = 0;
          if (config_.conf().allow_ips_.find(string(conn_ip)) == config_.conf().allow_ips_.end()) {
            CWX_ERROR(("Connect ip:%s is not valid ui ip, close it.", conn_ip));
            return -1;
          }
        }
      }

      bSuspendConn = true;
      if (0 > (ret = this->noticeTcpConnect(SVR_TYPE_CENTER,
        0,
        config_.conf().center_.getHostName().c_str(),
        config_.conf().center_.getPort(),
        false,
        1,
        1,
        NULL,
        NULL,
        config_.conf().center_timeout_millsecond_)))
      {
        CWX_ERROR(("Can't register connect for center: addr=%s, port=%d",
          config_.conf().center_.getHostName().c_str(),
          config_.conf().center_.getPort()));
        return -1;
      }
      //
      uint32_t conn_id = ret;
      agent_center_conns_[conn.getConnInfo().getConnId()] = pair<uint32_t, bool>(conn_id, false);
      center_agent_conns_[conn_id] = conn.getConnInfo().getConnId();
    } else {
      map<uint32_t, uint32_t>::iterator iter = center_agent_conns_.find(conn.getConnInfo().getConnId());
      if (iter == center_agent_conns_.end()) {
        return -1;//关闭连接
      }
      // 运行接受agent的数据
      if (0 != noticeResumeListen(iter->second)) {
        CWX_ASSERT(0);
        exit(-1);
      }
      map<uint32_t, pair<uint32_t, bool> >::iterator iter_center =  agent_center_conns_.find(iter->second);
      iter_center->second.second = true;
    }
    return 0;
  }

  int DcmdProxyApp::onConnClosed(CwxAppHandler4Msg& conn) {
    if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) {
      map<uint32_t, pair<uint32_t, bool> >::iterator iter = agent_center_conns_.find(conn.getConnInfo().getConnId());
      if (iter != agent_center_conns_.end()) {
        noticeCloseConn(iter->second.first);
        center_agent_conns_.erase(iter->second.first);
        agent_center_conns_.erase(iter);
      }
    } else {
      map<uint32_t, uint32_t>::iterator iter = center_agent_conns_.find(conn.getConnInfo().getConnId());
      if (iter != center_agent_conns_.end()) {
        noticeCloseConn(iter->second);
        agent_center_conns_.erase(iter->second);
        center_agent_conns_.erase(iter);
      }
    }
    return 0;
  }

  int DcmdProxyApp::onRecvMsg(CwxMsgBlock* msg,
    CwxAppHandler4Msg& conn, CwxMsgHead const& header, bool& )
  {
    uint32_t conn_id = 0;
    if (SVR_TYPE_AGENT == conn.getConnInfo().getSvrId()) {
      map<uint32_t, pair<uint32_t, bool> >::iterator iter = agent_center_conns_.find(conn.getConnInfo().getConnId());
      if (iter == agent_center_conns_.end()) {
        CWX_ASSERT(0);
        return -1;
      }
      if (!iter->second.second) return -1;
      conn_id = iter->second.first;
    } else {
      map<uint32_t, uint32_t>::iterator iter = center_agent_conns_.find(conn.getConnInfo().getConnId());
      if (iter == center_agent_conns_.end()) {
        CWX_ASSERT(0);
        return -1;
      }
      conn_id = iter->second;
    }
    CwxMsgHead local(header);
    CwxMsgBlock* relay = CwxMsgBlockAlloc::pack(local, msg->rd_ptr(), msg->length());
    relay->send_ctrl().setConnId(conn_id);
    return sendMsgByConn(relay);
  }

  void DcmdProxyApp::destroy(){
    if (data_buf_) {
      delete[] data_buf_;
      data_buf_ = NULL;
    }
    data_buf_len_ = 0;
    agent_center_conns_.clear();
    center_agent_conns_.clear();
    CwxAppFramework::destroy();
  }

}

