--dcmd_node change's event
delimiter $
drop trigger if exists dcmd_tr_insert_dcmd_node $
create trigger dcmd_tr_insert_dcmd_node after insert on dcmd_node for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('node', 1, new.nid, new.ip, new.sid, 0, 0, 0, 0, concat("host=",new.host), now(), 0);
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_update_dcmd_node $
create trigger dcmd_tr_update_dcmd_node after update on dcmd_node for each row
begin
  if new.host!=old.host or new.sid != old.sid then
    insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
      values('node', 2, new.nid, new.ip, new.sid, 0, 0, 0, 0, concat("host=",new.host), now(), 0);
  end if;
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_delete_dcmd_node $
create trigger dcmd_tr_delete_dcmd_node after delete on dcmd_node for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('node', 3, old.nid, old.ip, old.sid, 0, 0, 0, 0, concat("host=",old.host), now(), 0);
end $
delimiter ;


--dcmd_app change's event
delimiter $
drop trigger if exists dcmd_tr_insert_dcmd_app $
create trigger dcmd_tr_insert_dcmd_app after insert on dcmd_app for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('app', 1, 0, "", "", new.app_id, 0, 0, 0, concat("app_name=",new.app_name, ";app_type=", new.app_type), now(), 0);
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_update_dcmd_app $
create trigger dcmd_tr_update_dcmd_app after update on dcmd_app for each row
	begin
	  if new.app_name!=old.app_name or new.app_type != old.app_type then
	    insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
	      values('app', 2, 0, "", "", new.app_id, 0, 0, 0, concat("app_name=",new.app_name, ";app_type=", new.app_type), now(), 0);
	  end if;
	end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_delete_dcmd_app $
create trigger dcmd_tr_delete_dcmd_app after delete on dcmd_app for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('app', 3, 0, "", "", old.app_id, 0, 0, 0, concat("app_name=",old.app_name, ";app_type=", old.app_type), now(), 0);
end $
delimiter ;

--dcmd_service change's event
delimiter $
drop trigger if exists dcmd_tr_insert_dcmd_service $
create trigger dcmd_tr_insert_dcmd_service after insert on dcmd_service for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr', 1, 0, "", "", new.app_id, new.svr_id, 0, 0, concat("svr_name=",new.svr_name, ";svr_path=", new.svr_path, ";run_user=", new.run_user, ";node_multi_pool=", cast(new.node_multi_pool as char)), now(), 0);
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_update_dcmd_service $
create trigger dcmd_tr_update_dcmd_service after update on dcmd_service for each row
  begin
    if new.app_id!=old.app_id or new.svr_name != old.svr_name or new.svr_path != old.svr_path or new.run_user != old.run_user or new.node_multi_pool != old.node_multi_pool  then
      insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
        values('svr', 2, 0, "", "", new.app_id, new.svr_id, 0, 0, concat("svr_name=",new.svr_name, ";svr_path=", new.svr_path, ";run_user=", new.run_user, ";node_multi_pool=", cast(new.node_multi_pool as char)), now(), 0);
    end if;
  end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_delete_dcmd_service $
create trigger dcmd_tr_delete_dcmd_service after delete on dcmd_service for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr', 3, 0, "", "", old.app_id, old.svr_id, 0, 0, concat("svr_name=",old.svr_name, ";svr_path=", old.svr_path, ";run_user=", old.run_user, ";node_multi_pool=", cast(old.node_multi_pool as char)), now(), 0);
end $
delimiter ;

--dcmd_service_pool change's event
delimiter $
drop trigger if exists dcmd_tr_insert_dcmd_service_pool $
create trigger dcmd_tr_insert_dcmd_service_pool after insert on dcmd_service_pool for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr_pool', 1, 0, "", "", new.app_id, new.svr_id, new.svr_pool_id, 0, concat("svr_pool=",new.svr_pool, ";repo=", new.repo, ";env_ver=", new.env_ver, ";env_md5=", new.env_md5, ";env_passwd=", new.env_passwd), now(), 0);
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_update_dcmd_service_pool $
create trigger dcmd_tr_update_dcmd_service_pool after update on dcmd_service_pool for each row
  begin
    if new.svr_pool!=old.svr_pool or new.svr_id != old.svr_id or new.app_id != old.app_id or new.repo != old.repo or new.env_ver != old.env_ver or new.env_md5 != old.env_md5 or new.env_passwd != old.env_passwd then
      insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
        values('svr_pool', 2, 0, "", "", new.app_id, new.svr_id, new.svr_pool_id, 0, concat("svr_pool=",new.svr_pool, ";repo=", new.repo, ";env_ver=", new.env_ver, ";env_md5=", new.env_md5, ";env_passwd=", new.env_passwd), now(), 0);
    end if;
  end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_delete_dcmd_service_pool $
create trigger dcmd_tr_delete_dcmd_service_pool after delete on dcmd_service_pool for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr_pool', 3, 0, "", "", old.app_id, old.svr_id, old.svr_pool_id, 0, concat("svr_pool=",old.svr_pool, ";repo=", old.repo, ";env_ver=", old.env_ver, ";env_md5=", old.env_md5, ";env_passwd=", old.env_passwd), now(), 0);
end $
delimiter ;

--dcmd_service_pool_attr change's event
delimiter $
drop trigger if exists dcmd_tr_insert_dcmd_service_pool_attr $
create trigger dcmd_tr_insert_dcmd_service_pool_attr after insert on dcmd_service_pool_attr for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr_pool_attr', 1, 0, "", "", new.app_id, new.svr_id, new.svr_pool_id, 0, concat("attr_name=",new.attr_name, ";attr_value=", new.attr_value), now(), 0);
end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_update_dcmd_service_pool_attr $
create trigger dcmd_tr_update_dcmd_service_pool_attr after update on dcmd_service_pool_attr for each row
  begin
    if new.app_id!=old.app_id or new.svr_id != old.svr_id or new.attr_value != old.attr_value then
      insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
        values('svr_pool_attr', 2, 0, "", "", new.app_id, new.svr_id, new.svr_pool_id, 0, concat("attr_name=",new.attr_name, ";attr_value=", new.attr_value), now(), 0);
    end if;
  end $
delimiter ;

delimiter $
drop trigger if exists dcmd_tr_delete_dcmd_service_pool_attr $
create trigger dcmd_tr_delete_dcmd_service_pool_attr after delete on dcmd_service_pool_attr for each row
begin
  insert into dcmd_obj_change_event(obj_type,change_type,nid,ip,sid,app_id,svr_id,svr_pool_id,task_id,data,ctime,opr_uid) 
    values('svr_pool_attr', 3, 0, "", "", old.app_id, old.svr_id, old.svr_pool_id, 0, concat("attr_name=", old.attr_name, ";attr_value=", old.attr_value), now(), 0);
end $
delimiter ;
