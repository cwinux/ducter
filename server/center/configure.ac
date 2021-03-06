# Required initializer
AC_INIT

# Automake initialization
AM_INIT_AUTOMAKE(dcmd_center, 1.0)
m4_include([ax_cxx_compile_stdcxx_0x.m4])

# Add a test for a compiler.
AC_PROG_CC
AC_PROG_CXX
#AM_PROG_LIBTOOL
LT_INIT
AX_CXX_COMPILE_STDCXX_0X

if test "x$ax_cv_cxx_compile_cxx0x_cxx" = "xyes" ; then
    CXXFLAGS="$CXXFLAGS -std=c++0x"
fi


AC_DEFUN([LIBEVENT_DIR],[

    AC_ARG_WITH(
        cwinux,
        [  --with-cwinux[=DIR]        cwinux directory],
        ,
        [with_cwinux="no"]
    )
    AC_ARG_WITH(
        protobuf,
        [  --with-protobuf[=DIR]     protobuf directory],
        ,
        [with_protobuf="no"]
    )
    AC_ARG_WITH(
        expat,
        [  --with-expat[=DIR]     expat directory],
        ,
        [with_expat="no"]
    )
    AC_ARG_WITH(
        mysql,
        [  --with-mysql[=DIR]     mysql directory],
        ,
        [with_mysql="no"]
    )

    if test "$with_cwinux" = "no"; then
        AC_MSG_ERROR( You need to specify the cwinux directory using --with-cwinux)
    else
        # make sure that a well known include file exists
        if test -e $with_cwinux/app/CwxAppFramework.h; then
            cwinux_dir=$with_cwinux
            AC_MSG_RESULT(cwinux found!)
        else
            AC_MSG_ERROR( $with_cwinux not found.  Check the value you specified with --with-cwinux)
        fi
    fi

    if test "$with_protobuf" = "no"; then
        AC_MSG_ERROR( You need to specify the protobuf directory using --with-protobuf)
    else
        # make sure that a well known include file exists
        if test -e $with_protobuf/google/protobuf/message.h; then
            protobuf_dir=$with_protobuf
            AC_MSG_RESULT(protobuf found!)
        else
            AC_MSG_ERROR( $with_protobuf not found.  Check the value you specified with --with-protobuf)
        fi
    fi
    if test "$with_expat" = "no"; then
        AC_MSG_ERROR( You need to specify the expat directory using --with-expat)
    else
        # make sure that a well known include file exists
        if test -e $with_expat/expat.h; then
            expat_dir=$with_expat
            AC_MSG_RESULT(expat found!)
        else
            AC_MSG_ERROR( $with_expat not found.  Check the value you specified with --with-expat)
        fi
    fi
    if test "$with_mysql" = "no"; then
        AC_MSG_ERROR( You need to specify the mysql directory using --with-mysql)
    else
        # make sure that a well known include file exists
        if test -e $with_mysql/mysql.h; then
            mysql_dir=$with_mysql
            AC_MSG_RESULT(mysql found!)
        else
            AC_MSG_ERROR( $with_mysql not found.  Check the value you specified with --with-mysql)
        fi
    fi
])

LIBEVENT_DIR

AC_DEFINE(_LARGEFILE64_SOURCE)
AC_DEFINE(_FILE_OFFSET_BITS,64)
AC_DEFINE(_LARGEFILE64_SOURCE)
AC_DEFINE(_REENTRANT)
AC_DEFINE(_GNU_SOURC)


# The AC_SUBST macro causes the variable to be saved in config.status
AC_SUBST(cwinux_dir)
AC_SUBST(protobuf_dir)
AC_SUBST(mysql_dir)
AC_SUBST(expat_dir)


# Write config.status and the Makefile
AC_OUTPUT(Makefile)
