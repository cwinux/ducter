#ifndef __DCMD_PROXY_APP_H__
#define __DCMD_PROXY_APP_H__

/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/

#include <CwxAppFramework.h>
#include "dcmd_macro.h"
#include "dcmd_tss.h"
#include "dcmd_proxy_config.h"
#include "dcmd_proxy_def.h"

namespace dcmd {
const char* const kDcmdProxyVersion = "1.0";
const char* const kDcmdProxyModifyDate = "2017-10-13 22:30:00";

// proxy的app对象
class DcmdProxyApp : public CwxAppFramework{
  public:
    enum{
      // 服务类型
      SVR_TYPE_AGENT = CwxAppFramework::SVR_TYPE_USER_START,
      SVR_TYPE_CENTER = CwxAppFramework::SVR_TYPE_USER_START + 1
    };
    DcmdProxyApp();
    virtual ~DcmdProxyApp(); 
  public:
    // 初始化
    virtual int init(int argc, char** argv);
    // 时钟响应函数
    virtual void onTime(CwxTimeValue const& current);
    // signal响应函数
    virtual void onSignal(int signum);
    // 连接建立通知
    virtual int onConnCreated(CwxAppHandler4Msg& conn,
      bool& bSuspendConn, bool& bSuspendListen);
    // 连接关闭
    virtual int onConnClosed(CwxAppHandler4Msg& conn);
    // 收到消息的响应函数
    virtual int onRecvMsg(CwxMsgBlock* msg, CwxAppHandler4Msg& conn,
      CwxMsgHead const& header, bool& bSuspendConn);
  public:
    // 计算机的时钟是否回调
    inline bool isClockBack(uint32_t& last_time, uint32_t now) const {
      if (last_time > now + 1){
        last_time = now;
        return true;
      }
      last_time = now;
      return false;
    }
    // 获取配置文件
    DcmdProxyConfig const* getProxyConf() const { return  &config_; }
    // 获取package的buf，返回NULL表示失败
    inline char* getBuf(uint32_t size){
      if (data_buf_len_ < size){
        delete [] data_buf_;
        data_buf_ = new char[size];
        data_buf_len_ = size;
      }
      return data_buf_;
    }
  protected:
    // 重载运行环境设置API
    virtual int initRunEnv();
    virtual void destroy();
  private:
    // 配置文件
    DcmdProxyConfig                       config_;
    // 数据buf
    char*                                 data_buf_;
    // 数据buf的空间大小
    uint32_t                              data_buf_len_;
    // agent连接对应的center的连接
    map<uint32_t, pair<uint32_t, bool> >  agent_center_conns_;
    // center连接对应的agent的连接
    map<uint32_t, uint32_t>               center_agent_conns_;
    // 错误buf
    char                                  err_2k_[kDcmd2kBufLen];
};
}  // dcmd
#endif

