##  Process this file with automake to create Makefile.in

includedir =
pkgconfigdir =

BUILD_DATE=$(shell date +%F)


bin_PROGRAMS = dcmd_agent
dcmd_agent_CPPFLAGS = -D_BUILD_DATE="$(BUILD_DATE)" -W -Wall
dcmd_agent_SOURCES =dcmd_proxy_agent_mgr.cc\
		dcmd_proxy.cc\
		dcmd_proxy_config.cc\
		dcmd_proxy_handler.cc\
		dcmd_proxy_app.cc\
		dcmd_proxy_center_mgr.cc\
		dcmd_proxy_def.cc\
		../cmn/dcmd_def.cc\
		../cmn/dcmd_script_driver.cc\
		../cmn/dcmd_tss.cc\
                ../api/protocpp/dcmd_api_cmn.pb.cc\
                ../api/protocpp/dcmd_api_agent.pb.cc\
                ../api/protocpp/dcmd_api_proxy.pb.cc
dcmd_agent_LDADD=
dcmd_agent_LDFLAGS=
install-exec-hook:
	cp -f $(top_srcdir)/bin/start.sh $(bindir)/start.sh
	cp -f $(top_srcdir)/bin/stop.sh  $(bindir)/stop.sh
	cp -f $(top_srcdir)/bin/install.sh  $(bindir)/install.sh 
	cp -n $(top_srcdir)/bin/dcmd_proxy.conf $(bindir)/dcmd_proxy.conf
	chmod 755 $(bindir)/start.sh
	chmod 755 $(bindir)/stop.sh
	chmod 755 $(bindir)/install.sh 

LIBS=-lcwxapp -lcwxnet -lcwxcmn -lprotobuf -lpthread -lz -ldl -lrt 
AM_CXXFLAGS=-I@cwinux_dir@/app -I@cwinux_dir@/cmn -I@cwinux_dir@/net -I@protobuf_dir@ -I. -I../cmn -I../api/protocpp

## Clean up template repositories, etc.
clean-local:
	-rm -f *~ *.bak *.rpo *.sym lib*.*_pure_* core core.*
