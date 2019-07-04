<?php
class mysql{
	//database connect
	function connect(){
		global $data;
		$conn=mysql_connect($data['ip'],$data['user'],$data['pass']) or die('连接mysql 错误！');
		$r=mysql_select_db($data['dbname'],$conn) or die('连接数据库错误 message:'.mysql_error());
		mysql_query('set names "utf8"');
		return $r;
	}
	//执行sql 语句
	function query($sql){
		self::connect();
		$r=mysql_query($sql) or die('sql 语句错误！('.$sql.')message：'.mysql_error());
		return $r;
	}
	/*
		抓取数量
		$twhere 表名 和 条件
	*/
	function getnum($twhere){
		$sql="select * from ".$twhere;
		$r=self::query($sql);
		$num=mysql_num_rows($r);
		return $num;
	}
	/*
		抓取某一条记录
		$font 获取的字段
		$twhere 表名 和条件
	*/
	function getrow($font,$twhere){
		$sql='select '.$font.' from '.$twhere;
		$r=self::query($sql);
		//echo $sql.'<br/>';
		$ro=mysql_fetch_array($r);
		return $ro;
	}
	/*
		抓取所有记录
		$font 显示的字段
		$twhere 表名 和条件 
	*/
	function getall($font,$twhere){
		$sql='select '.$font.' from  '.$twhere;
		$r=self::query($sql);
		$ro=array();
		while($row=mysql_fetch_array($r)){
			$ro[]=$row;
		}
		return $ro;
	}
	/*
		插入记录 正常
		$tablename 表名
		$fontstr 插入的内容，以数组的形式  数组例如 ：array(xxx=>xxx1,aaa=>aaa2)
	*/
	function insert($tablename,$fontstr){
		foreach($fontstr as $t=>$v){
			$sqlstr_fname.='`'.$t.'`,';
			$sqlstr_vname.='"'.$v.'",';
		}
		$sqlstr_fname=substr($sqlstr_fname,0,-1);
		$sqlstr_vname=substr($sqlstr_vname,0,-1);
		$sql='insert into '.$tablename.' ('.$sqlstr_fname.')values('.$sqlstr_vname.') ';
		//echo $sql.'<br/>';
		self::query($sql);
		return true;
	}
	/*
		插入记录 返回插入记录的ID
		$tablename 表名
		$fontstr 插入的内容，以数组的形式  数组例如 ：array(xxx=>xxx1,aaa=>aaa2)
	*/
	function insert_id($tablename,$fontstr){
		foreach($fontstr as $t=>$v){
			$sqlstr_fname.='`'.$t.'`,';
			$sqlstr_vname.='"'.$v.'",';
		}
		$sqlstr_fname=substr($sqlstr_fname,0,-1);
		$sqlstr_vname=substr($sqlstr_vname,0,-1);
		$sql='insert into '.$tablename.' ('.$sqlstr_fname.')values('.$sqlstr_vname.') ';
		self::query($sql);
		return mysql_insert_id();
	}
	/*
		更新
		$tablename  要更新的表名
		$fontstr 要更新的数组 数组例如：array(xxx=>xxx1,aaaa=>aaa2);
		$where  更新条件 f:where xxx=1;
	*/
	function getupdate($tablename,$fontstr,$where){
		foreach($fontstr as $t=>$v){
			$sqlstr.='`'.$t.'`='.'"'.$v.'",';
		}
		$sqlstr=substr($sqlstr,0,-1);
		$sql='update '.$tablename.' set '.$sqlstr.' '.$where;
		//echo $sql.'<br/>';
		self::query($sql);
		return true;
	}
	function getdel($twhere){
		$sql='delete from '.$twhere;
		mysql::query($sql);
		return true;
	}
}
?>