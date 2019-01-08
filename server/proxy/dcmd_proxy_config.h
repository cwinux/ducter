#ifndef __DCMD_PROXY_CONFIG_H__
#define __DCMD_PROXY_CONFIG_H__
/*
版权声明：
    本软件遵循GNU General Public License version 2（http://www.gnu.org/licenses/gpl.html），
    联系方式：email:cwinux@gmail.com；微博:http://weibo.com/cwinux
*/
#include <CwxCommon.h>
#include <CwxIniParse.h>
#include <CwxHostInfo.h>
#include "dcmd_proxy_def.h"

namespace dcmd{
// 配置文件的数据对象
class DcmdProxyConfigData{
 public:
  DcmdProxyConfigData(){
    center_timeout_millsecond_ = 1000;
    is_debug_ = false;
    log_file_num_ = kDefLogFileNum;
    log_file_msize_ = kDefLogFileMSize;
	}
 private:
   DcmdProxyConfigData(DcmdProxyConfigData const& ){}
   DcmdProxyConfigData& operator=(DcmdProxyConfigData const&){
     return *this;
   }
 public:
  // 工作目录
  string                     work_home_;
  // 监听地址
  CwxHostInfo           	   listen_;
  // 控制中心
  CwxHostInfo                center_;
  // 控制中心连接超时
  uint32_t                   center_timeout_millsecond_;
  // 运行连接proxy的ip地址，可以为a,b,c类段
  set<string>                allow_ips_;
  // 日志文件的数量
  uint32_t                   log_file_num_;
  // 日志文件的大小
  uint32_t                   log_file_msize_;
  // 是否打开调试开关
  bool                       is_debug_;
};

class DcmdProxyConfig {
 public:
  DcmdProxyConfig() {
    err_2k[0] = 0x00;
  }
  ~DcmdProxyConfig(){
  }
 public:
  //加载配置文件.-1:failure, 0:success
  int init(string const& cnf_file);
  //输出加载的配置文件信息
  void outputConfig() const;
 public:
  // 获取common配置信息
  inline DcmdProxyConfigData const& conf() const {
    return  conf_;
  }
  // 获取配置文件加载的失败原因
  inline char const* err_msg() const{
    return err_2k;
  };
 private:
  // proxy的配置信息
  DcmdProxyConfigData    conf_;
  // 配置解析器
  CwxIniParse		         parser_;
  // 错误信息
  char                   err_2k[kDcmd2kBufLen];
};
} // dcmd
#endif

