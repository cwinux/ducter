insert into dwb_app_all(company,depart,seq,app_id,svr_id,svr_pool_id,app_alias,app_name,svr_alias,svr_name,user,tel,svr_tree,pool_alias,pool,domain,vip,ip,my_ip,my_port,my_db,ora_ip,ora_port,mong_ip,mong_port,redis_ip,redis_port,cbase_ip,cbase_bucket,gluster_ip,gluster_v,mq_ip,mq_name) select * from dwb_app_all_bak;



// 形成设备池
insert into dcmd_node_group(ngroup_name,ngroup_alias,gid,comment,utime,ctime,opr_uid) select distinct concat(company,"_",app_alias),concat(company,"_",app_alias),52,"",now(),now(),0 from dwb_app_all;

// 形成设备
insert into dwb_node(ngroup_id,ngroup_name,ip) select distinct b.ngroup_id,b.ngroup_name, a.ip from dwb_app_all a, dcmd_node_group b where length(a.ip)>4 and concat(a.company,"_",a.app_alias)=b.ngroup_name;
insert into dcmd_node(ip,ngroup_id,host,sid,did,bend_ip,public_ip,comment,utime,ctime,opr_uid) select distinct ip,0,ip,ip,ip,ip,"0.0.0.0","",now(),now(),0 from dwb_node;
update dcmd_node a, dwb_node b set a.ngroup_id=b.ngroup_id where a.ip=b.ip;
// 形成部门
insert into dcmd_department(depart_name,comp_id,comment,utime,ctime,opr_uid) select distinct a.depart, b.comp_id,"",now(),now(),0 from dwb_app_all a, dcmd_company b where a.company=b.comp_name;
// 形成员工
insert into dcmd_user(comp_id,username,passwd,sa,admin,depart_id,tel,email,state,comment,utime,ctime,opr_uid) select distinct a.comp_id,b.user,b.user,0,0,0,b.tel,concat(b.user,"@le.com"),1,"",now(),now(),0 from dwb_app_all b, dcmd_department a, dcmd_company c where length(b.user)>1 and a.depart_name=b.depart and b.company=c.comp_name and a.comp_id=c.comp_id;
update dcmd_user a, dcmd_department b, dwb_app_all c  set a.depart_id=b.depart_id where c.depart=b.depart_name and a.username=c.user;
// 形成用户组
insert into dcmd_group(comp_id,gname,gtype,comment,utime,ctime,opr_uid)  select distinct b.comp_id,concat(a.company,"_",a.depart,"_",a.app_alias),2,"",now(),now(),0 from dwb_app_all a, dcmd_company b where a.company=b.comp_name;
// 形成用户组的用户
insert into dcmd_user_group(uid,gid,comment,utime,ctime,opr_uid,is_leader) select distinct a.uid,b.gid,"",now(),now(),0,1 from dcmd_user a, dcmd_group b ,dwb_app_all c where a.username=c.user and b.gname=concat(c.company,"_",c.depart,"_",c.app_alias) and a.comp_id=b.comp_id;
// 形成产品
insert into dcmd_app(app_name,app_alias,service_tree,sa_gid,svr_gid,comp_id,depart_id,comment,utime,ctime,opr_uid,app_type) select distinct a.app_name,a.app_alias,"",52,b.gid,b.comp_id,c.depart_id,"",now(),now(),0,"app" from dwb_app_all a,dcmd_group b,dcmd_department c where b.gname=concat(a.company,"_",a.depart,"_",a.app_alias) and a.depart=c.depart_name and c.comp_id=b.comp_id;
update dwb_app_all a, dcmd_app b set a.app_id=b.app_id where a.app_name=b.app_name;
// 形成服务
insert into dcmd_service(svr_name,svr_alias,service_tree,res_id,svr_path,run_user,app_id,node_multi_pool,owner,comment,utime,ctime,opr_uid) select distinct a.svr_name,a.svr_alias,a.svr_tree,"","/var/app","root",a.app_id,1,0,"",now(),now(),0 from dwb_app_all a;
update dwb_app_all a, dcmd_service b set a.svr_id=b.svr_id where a.app_id=b.app_id and a.svr_name=b.svr_name;
//形成服务池
insert into dcmd_service_pool(svr_pool,svr_id,app_id,repo,env_ver,comment,utime,ctime,opr_uid) select distinct pool,svr_id,app_id,"","","",now(),now(),0 from dwb_app_all where length(ip)>1;
update dwb_app_all a, dcmd_service_pool b set a.svr_pool_id=b.svr_pool_id where a.app_id=b.app_id and a.svr_id=b.svr_id and a.pool=b.svr_pool;
update dcmd_service_pool set svr_pool="unknown" where  length(svr_pool) < 1;
//插入设备
insert into dcmd_service_pool_node(svr_pool_id,svr_id,nid,app_id,ip,utime,ctime,opr_uid) select distinct a.svr_pool_id,a.svr_id,b.nid,a.app_id,a.ip,now(),now(),0 from dwb_app_all a, dcmd_node b where a.ip=b.ip;

// 产品的设备池
insert into dcmd_app_node_group(app_id,ngroup_id,ctime,opr_uid) select distinct a.app_id,b.ngroup_id,now(),0 from dcmd_service_pool_node a, dcmd_node b where a.ip=b.ip and app_id <>56;

// 导入dns
insert into dwb_res_dns(name) select distinct domain from dwb_app_all where length(domain)>1;
delete from dwb_res_dns where id in(....);
insert into dcmd_res_dns(res_id,res_name,res_order,is_public,name,I2,I3,I4,I5,tag,comp_id,contact,state,comment,ctime,stime,opr_uid) select  concat("DNS_",cast(id as char)), name,id,1,name,"","","","","",0,"",1,"",now(),now(),0 from dwb_res_dns;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"DNS",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_dns b where a.domain=b.name;

// 导入vip
insert into dwb_res_lvs(vip) select distinct vip from dwb_app_all where length(vip)>1;
delete from dwb_res_lvs where id in ();
insert into dcmd_res_lvs(res_id,res_name,res_order,is_public,vip,comp_id,contact,state,comment,ctime,stime,opr_uid) select  concat("LVS_",cast(id as char)), vip,id,1,vip,0,"",1,"",now(),now(),0 from dwb_res_lvs;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"LVS",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_lvs b where a.vip=b.vip;

// 导入mysql
insert into dwb_res_mysql(server, port, db) select distinct my_ip,my_port,my_db from dwb_app_all where length(my_ip)>1;
delete from dwb_res_mysqlwhere id in ();
insert into dcmd_res_mysql(res_id,res_name,res_order,is_public,server,port,db,comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("MySQL_",cast(id as char)), concat(server,":",port,":",db), id, 1, server, port, db, 0, "", 1, "", now(), now(),0 from dwb_res_mysql;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"MySQL",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_mysql b where a.my_ip=b.server and a.my_port=b.port and a.my_db=b.db;
// 导入oracle
insert into dwb_res_oracle(server, port) select distinct ora_ip,ora_port from dwb_app_all where length(ora_ip)>1 or length(ora_port)>1;
insert into dcmd_res_oracle(res_id,res_name,res_order,is_public,cluster, cluster_ip, schema_name, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Oracle_",cast(id as char)), concat(server,":",port), id, 1, server, server, port, 0, "", 1, "", now(), now(),0 from dwb_res_oracle;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Oracle",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_oracle b where a.ora_ip=b.cluster_ip and a.ora_port=b.schema_name ;
// 导入mongo
insert into dwb_res_mongo(server, port) select distinct mong_ip, mong_port from dwb_app_all where length(mong_ip)>1 or length(mong_port)>1;
insert into dcmd_res_mongo(res_id,res_name,res_order,is_public,cluster, instance, port, instance_num, backup_cluster, backup_port, backup_instance_num, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Mongo_",cast(id as char)), concat(server,":",port), id, 1, "default", server, port,0, "", "",0,0,"",1, "",now(), now(),0 from dwb_res_mongo;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Mongo",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_mongo b where a.mong_ip=b.instance and a.mong_port=b.port ;
// 导入redis
insert into dwb_res_redis(server, port) select distinct redis_ip, redis_port from dwb_app_all where length(redis_ip)>1 or length(redis_port)>1;
insert into dcmd_res_redis(res_id,res_name,res_order,is_public,cluster, port, quota, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Redis_",cast(id as char)), concat(server,":",port), id, 1, server, port,0, 0,"",1, "",now(), now(),0 from dwb_res_redis;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Redis",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_redis b where a.redis_ip=b.cluster and a.redis_port=b.port ;
// 导入cbase
insert into dwb_res_cbase(server, bucket) select distinct "", cbase_bucket from dwb_app_all where length(cbase_bucket)>1;
insert into dcmd_res_cbase(res_id,res_name,res_order,is_public,cluster, bucket, quota, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Cbase_",cast(id as char)), concat(server,":",bucket), id, 1, server, bucket,0, 0,"",1, "",now(), now(),0 from dwb_res_cbase;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Cbase",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_cbase b where a.cbase_bucket=b.bucket;
// 导入gluster
insert into dwb_res_gluster(server, volume) select distinct "dfs-zb18", gluster_v from dwb_app_all where length(gluster_v)>1;
insert into dcmd_res_gluster(res_id,res_name,res_order,is_public,cluster, volume, quota, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Gluster_",cast(id as char)), concat(server,":",volume), id, 1, server, volume,0, 0,"",1, "",now(), now(),0 from dwb_res_gluster;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Gluster",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_gluster b where a.gluster_v=b.volume;
// 导入mq
insert into dwb_res_mq(server, mq) select distinct mq_ip, mq_name from dwb_app_all where length(mq_name)>1;
insert into dcmd_res_mq(res_id,res_name,res_order,is_public,cluster, queue, quota, comp_id,contact,state,comment,ctime,stime,opr_uid) select concat("Mq_",cast(id as char)), concat(server,":",mq), id, 1, server, mq,0, 0,"",1, "",now(), now(),0 from dwb_res_mq;
insert into dcmd_app_res(app_id,svr_id,svr_pool_id, res_type,res_id,res_name,is_own,ctime,opr_uid) select distinct a.app_id,a.svr_id, a.svr_pool_id,"Mq",b.res_id,b.res_name,1,now(),0 from dwb_app_all a, dcmd_res_mq b where a.mq_ip=b.cluster and a.mq_name=b.queue;




