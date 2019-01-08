# ducter
WEB 环境
PHP
  PHP版本需要>=5.4
  可以yum源安装，也可以源码安装。
PHP模块
  WEB 环境需要的模块如下：
  php-mysql：
      yum安装： #yum install php-mysql
  php-mcrypt
      yum安装： #yum install php-mcrypt
  php-mbstring
      yum安装： #yum install php-mbstring
  php-protobuf
      源码安装过程如下
      #git clone https://github.com/allegro/php-protobuf.git
      #cd php-protobuf
      #phpize
      #./configure
      #make
      #make test
      #make install
      #echo extension=protobuf.so > /etc/php.d/protobuf.ini

WEB 安装与配置
安装web server代码
 创建ducter安装目录
 #mkdir /usr/share/ducter
 从github上下载对应版本的代码（1.0.3版本）
 #cd /usr/share/ducter
 #wget https://github.com/dengleitju/ducter-web/archive/v1.0.3.tar.gz -O ducter_web1.0.3.tar.gz
 #tar -zxvf ducter_web1.0.3.tar.gz
 #mv ducter-web-1.0.3/basic   ./
 #rm –rf ducter-web-1.0.3
 #cd basic
 #unzip vendor.zip 
 #rm vendor.zip
 #chow –R www:www /usr/share/ducter   //注意www为apache服务启动帐号
修改web server配置
 修改ducter配置，配置mysql数据库连接信息
 #vim /usr/share/ducter/basic/config/db.php
 配置的内容，注意：该数据库连接帐号需要有读，写，删，锁数据库权限。
   return [
     'class' => 'yii\db\Connection',
     'dsn' => 'mysql:host=127.0.0.1;dbname=dcmd',    //正确设置数据库的IP（host）、数据库名
     'username' => 'dcmd',                           //正确设置数据库的用户名
     'password' => 'dcmd123456',                     //正确设置数据库用户的passwd
     'charset' => 'utf8',
 ];
 修改web server连接dcmd_center的配置
 vi /usr/share/ducter/basic/common/interface.php 
 修改如下的内容，经CENTER_USER与CENTER_PASSWD设置为的dcmd_center配置文件dcmd_center.conf中ui_user与ui_passwd的值
 const CENTER_USER = 'dcmd';  // 对应dcmd_center.conf中的ui_user
 const CENTER_PASSWD = 'dcmd'; // 对应dcmd_center.conf中的ui_passwd

修改apache配置
 在apache的http.conf中，增加以下内容：
 #vim /etc/http/conf/http.conf
   DocumentRoot "/usr/share/ducter/basic/web"
   Alias /ducter /usr/share/ducter/basic/web
   <Directory /usr/share/ducter/basic/web>
       Options FollowSymLinks
       DirectoryIndex index.php
       Order Allow,Deny
       allow from all
       Options  FollowSymLinks
       AllowOverride none
   </Directory>
  重启apache
  #service apache restart
数据库安装与初始化
mysql server安装
 若已经安装，则可以跳过这一步
   #sudo yum install mysql-server
 启动Mysql
   /ect/rc.d/init.d/mysqld start
创建dcmd数据库
 以root登陆数据库
   #/usr/bin/mysql –uroot –p
   mysql>use mysql;
   mysql>update user set passwd=passwd(‘root123456’) where user = ‘root’;
   mysql>flush privileges;
   mysql>create database dcmd default character utf8;
   mysql>exit;
创建数据库表及初始数据
  导入ducter数据库文件在ducter后台的interface目录下，名字为dcmd.sql
  #mysql –uroot –proot的密码 dcmd < dcmd.sql

  初始数据中包含如下初始化数据：
  1.任务脚本
install_by_svn: 通过svn安装上线任务模板样例。
test_process: 测试任务进度模板样例。
test_task_env: 测试任务环境模板样例。
