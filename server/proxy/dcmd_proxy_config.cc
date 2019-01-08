#include "dcmd_proxy_config.h"
#include <CwxLogger.h>
namespace dcmd {

int DcmdProxyConfig::init(string const& conf_file) {
  string value;
  string errmsg;
  if (!parser_.load(conf_file)){
    strcpy(err_2k, parser_.getErrMsg());
    return -1;
  }
  //load proxy:home
  if (!parser_.getAttr("proxy", "home", value) || !value.length()){
    snprintf(err_2k, kDcmd2kBufLen,
      "Must set [proxy:home] for running path.");
    return -1;
  }
  conf_.work_home_ = value;
	if ('/' != value[value.length()-1]) conf_.work_home_ +="/";
  //load proxy:listen
  if (!parser_.getAttr("proxy", "listen", value) || !value.length()){
    snprintf(err_2k, kDcmd2kBufLen, "Must set [proxy:listen]");
    return -1;
  }
  CwxCommon::trim(value);
  if (value.length()){
    if (!dcmd_parse_host_port(value, conf_.listen_)){
      snprintf(err_2k, kDcmd2kBufLen,
        "Invalid listen format:%s, it should be [host:port] format.",
        value.c_str());
      return -1;
    }
  } else {
    snprintf(err_2k, kDcmd2kBufLen, "Must set [proxy:listen]");
    return -1;
  }

  //load proxy:center
  if (!parser_.getAttr("proxy", "center", value) || !value.length())
  {
    snprintf(err_2k, kDcmd2kBufLen, "Must set [proxy:center]");
    return -1;
  }
  CwxCommon::trim(value);
  if (value.length()) {
    if (!dcmd_parse_host_port(value, conf_.center_)) {
      snprintf(err_2k, kDcmd2kBufLen,
        "Invalid center format:%s, it should be [host:port] format.",
        value.c_str());
      return -1;
    }
  }
  else {
    snprintf(err_2k, kDcmd2kBufLen, "Must set [proxy:center]");
    return -1;
  }
  // load proxy:allow_net
  conf_.allow_ips_.clear();
  if (parser_.getAttr("proxy", "allow_net", value) && value.length()) {
    list<string> items;
    CwxCommon::split(value, items, ';');
    list<string>::iterator iter = items.begin();
    string ip;
    while (iter != items.end()) {
      ip = *iter;
      CwxCommon::trim(ip);
      conf_.allow_ips_.insert(ip);
      ++iter;
    }
  }

  //load proxy:center_timeout_millsecond
  if (parser_.getAttr("proxy", "center_timeout_millsecond", value) && value.length())
  {
    conf_.center_timeout_millsecond_ = strtoul(value.c_str(), NULL, 10);
    if (conf_.center_timeout_millsecond_ < 500)
      conf_.center_timeout_millsecond_ = 500;
  }
  //load proxy:log_file_num
  if (parser_.getAttr("proxy", "log_file_num", value) && value.length())
  {
    conf_.log_file_num_ = strtoul(value.c_str(), NULL, 10);
    if (conf_.log_file_num_ < kMinLogFileNum )
      conf_.log_file_num_ = kMinLogFileNum;
    if (conf_.log_file_num_ > kMaxLogFileNum )
      conf_.log_file_num_ = kMaxLogFileNum;
  }
  //load proxy:log_file_msize
  if (parser_.getAttr("proxy", "log_file_msize", value) &&
    value.length())
  {
    conf_.log_file_msize_ = strtoul(value.c_str(), NULL, 10);
    if (conf_.log_file_msize_ < kMinLogFileMSize)
      conf_.log_file_msize_ = kMinLogFileMSize;
    if (conf_.log_file_msize_ > kMaxLogFileMSize)
      conf_.log_file_msize_ = kMaxLogFileMSize;
  }

  //load agent:debug
  if (!parser_.getAttr("proxy", "debug", value) || !value.length()){
    conf_.is_debug_ = false;
  }else{
    conf_.is_debug_ = (value=="yes")?true:false;
  }
  return 0;
}

void DcmdProxyConfig::outputConfig() const
{
  CWX_INFO(("*****************begin proxy conf*******************"));
  CWX_INFO(("home=%s", conf_.work_home_.c_str()));
  CWX_INFO(("listen=%s:%u", conf_.listen_.getHostName().c_str(),
    conf_.listen_.getPort()));
  CWX_INFO(("center=%s:%u", conf_.center_.getHostName().c_str(),
    conf_.center_.getPort()));
  CWX_INFO(("center_timeout_millsecond=%u", conf_.center_timeout_millsecond_));
  string value;
  set<string>::const_iterator iter = conf_.allow_ips_.begin();
  while (iter != conf_.allow_ips_.end()) {
    if (value.length()) value += ";";
    value += *iter;
    ++iter;
  }
  CWX_INFO(("allow_net=%s", value.length() ? value.c_str() : ""));
  CWX_INFO(("log_file_num=%u", conf_.log_file_num_));
  CWX_INFO(("log_file_msize=%u", conf_.log_file_msize_));
  CWX_INFO(("debug=%s", conf_.is_debug_?"yes":"no"));
  CWX_INFO(("*****************end proxy conf*******************"));
}
}

