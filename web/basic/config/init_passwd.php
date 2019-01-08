<?php
if ($argc != 2) { 
die("Usage: init_passwd.php user_name\n"); 
} 
$db=include("db.php");
$pdo = new PDO($db["dsn"].";charset=".$db["charset"],$db["username"],$db["password"]); 
$sql="select uid,username,passwd,ctime from dcmd_user where username='".$argv[1]."'";
$rs = $pdo->query($sql); 
$user_exist=false;
while($row = $rs->fetch()){ 
  $user_exist=true;
  $new_passwd=md5("123456".$argv[1].strval($row["ctime"]));
  break;
} 
if ($user_exist){
  $sql="update dcmd_user set passwd='".$new_passwd."' where username='".$argv[1]."'";
  if($pdo->exec($sql)){ 
    printf("Success to init password for [123456] to %s\n", $new_passwd);
  }else{
    printf("Failed to init password, err:\n");
    print_r($pdo->errorInfo());
  } 
}else{
  printf("User[%s] doesn't exist.", $argv[1]);
}

?>
