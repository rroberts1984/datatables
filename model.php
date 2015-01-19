<?php
class Model {
	function datatablesGetCount($baseSql,$where=NULL){
		$sql = "
			SELECT 
				count(*) as Count 
			FROM
				($baseSql";
		if($where){
			$sql.=" $where";
		}
		$sql.=") f";
		$res		= $this->query($sql);
		if($res) return $res['Count'];
		return 0;
	}
	function runDatatablesSql($sql){
		$res		= $this->query($sql);
		$return	= array();
		while($row = $this->fetch_array($res)){
			$return[] = $row;
		}
		if(!empty($return)) return $return;
		return false;
	}
}