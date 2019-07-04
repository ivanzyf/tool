<?php
/*
 * 说明文档
 * 1.导入数据是按正序从数据库里读，然后在写入
 *
 * */
class daorumysql{
    function __construct()
    {

        /*
        $this->db1_host=$db1_host;
        $this->db1_user=$db1_user;
        $this->db1_pwd=$db1_pwd;
        $this->db1_name=$db1_name;
        $this->db2_host=$db2_host;
        $this->db2_user=$db2_user;
        $this->db2_pwd=$db2_pwd;
        $this->db2_name=$db2_name;
        */

    }
    public function contact($db_host,$db_user,$db_pwd,$db_name){
        $this->pdo= new PDO("mysql:host=" . $db_host . ";dbname=" . $db_name,$db_user,$db_pwd);//创建一个pdo对象
        $this->pdo->exec("set names 'utf8'");
        return $this;
    }
    public function fetchyuan($fromtablename,$countnum){
        $sql = "select * from `".$fromtablename."`  order by id asc limit 0,".$countnum;
        $r=$this->pdo->query($sql);
        $this->pdo=$r->fetchAll();
        return $this;
    }
    public function iquery($sql){
        $this->pdo->exec($sql)or die('error:'.$sql);
        return $this;
    }


}
//导入源数据库信息
$db1_host='127.0.0.1';
$db1_user='root';
$db1_pwd='123';
$db1_name='aaa';
//导入目标数据库信息
$db2_host='127.0.0.1';
$db2_user='root';
$db2_pwd='123456';
$db2_name='bbb';

$countnum=10; //最大执行多少条
$fromtablename='wechat_fans';  //源表
$totablename='wechat_fans';   //目标表
//需要导入的字段  key 值为源字段  value 为目标字段
$dbarr=array(
    'nickname'=>'nickname',
    'openid'=>'open_id',
    'headimgurl'=>'headimgurl',
    'province'=>'province',
    'city'=>'city'

);

$selectdata=new daorumysql();
$insertdata=new daorumysql();
$fetchtable=$selectdata->contact($db1_host,$db1_user,$db1_pwd,$db1_name)->fetchyuan($fromtablename,$countnum);
$db2=$insertdata->contact($db2_host,$db2_user,$db2_pwd,$db2_name);
/*
foreach ($dbarr as $item=>$value){
    echo $item.'<br/>';
}
*/


//循环遍历源数据
$i=0;
$showdata=$fetchtable->pdo;
foreach ($showdata as $item=>$value){
    $yfont='';
    $tfont='';
    $isql='';
    $i++;
    foreach ($dbarr as $itmefont=>$valuefont){
        $tfont.=' `'.$valuefont.'` ,';
        $yfont.=' "'.$value[$itmefont].'",';
        //echo $itmefont.'-';
    }
    $yfont=substr($yfont,0,-1);
    $tfont=substr($tfont,0,-1);
    //echo $value['nickname'].'<br/>';
    $isql='insert into `'.$totablename.'`  ('.$tfont.')values( '.$yfont.' )';
    //echo $isql;
    $db2->iquery($isql);

}
echo '插入条数：'.$i.'<br/>';


?>
