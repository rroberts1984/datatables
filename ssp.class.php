<?php

class SSP {
	
	static function limit ($request)
	{
		$limit = '';

		if ( isset($request['start']) && $request['length'] != -1 ) {
			$limit = " LIMIT ".intval($request['start']).", ".intval($request['length']);
		}

		return $limit;
	}

	static function order($request,$columns)
	{
		$order = '';

		if (isset($request['order']) && count($request['order'])) {
			$order = "";
			$i=0;
			foreach($request['order'] as $req_order){
				if($i == 0){
					$order .= " ORDER BY `".$columns[$req_order['column']]."` ".$req_order['dir'];
					$i++;
				}else{
					$order.=", `".$columns[$req_order['column']]."` ".$req_order['dir'];
				}
			}
		}

		return $order;
	}

	static function filter ($model,$request,$searchable)
	{
		$where = "";
		if(isset($request['search']) && $request['search']['value'] != ''){
			$where = " WHERE";
			$i=0;
			foreach($searchable as $column){
				$searchvalue = '%'.$request['search']['value'].'%'; //be sure to use an escape here for $request['search']['value']
				if($i == 0){
					$where.=" `".$column."` LIKE ".$searchvalue."";
				}else{
					$where.=" OR `".$column."` LIKE ".$searchvalue."";
				}
				$i++;
			}
		}
		return $where;
	}
	
	static function buildSubquery($baseSql){
		return "
			SELECT
				* 
			FROM (".$baseSql.")b";
	}

	static function buildTable($model,$request,$baseSql,$columns,$searchable=NULL,$functions=array())
	{
		$sql		= self::buildSubquery($baseSql);
		$where	= self::filter($model,$request,$searchable);
		$order	= self::order($request,$columns);
		$limit	= self::limit($request);
		$data	= $model->runDatatablesSql($sql.$where.$order.$limit);
		if(!empty($functions)){
			if($data)$data	= self::runDataFunctions($model,$data,$functions);
		}
		return array(
			"draw"			=> intval($request['draw']),
			"recordsTotal"		=> intval($model->datatablesGetCount($sql)),
			"recordsFiltered"	=> intval($model->datatablesGetCount($sql,$where)),
			"data"			=> $data
		);
	}
	
	static function runDataFunctions($model,$data,$functions){
		foreach($data as &$row){
			foreach($functions as $function){
				$row = self::$function['function']($model,$row,$function);
			}
		}
		return $data;
	}
	
	static function editRow($model,$row,$function){
		$rowTemp = $function['replace'];
		foreach($function['params'] as $param){
			$rowTemp = preg_replace("~\{".$param."\}~is",$row[$param],$rowTemp);
		}
		$row[$function['target']] = $rowTemp;
		return $row;
	}
}