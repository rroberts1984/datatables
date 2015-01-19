<?php
class Controller {
	function galleries(){
		
	}
	
	function galleries_table(){
		//ajax call function
		if(!$_REQUEST)return false;
		$baseSql = "
			SELECT 
				CASE g.type 
					WHEN 1 THEN 'Gallery 1'
					WHEN 2 THEN 'Gallery 2'
					WHEN 3 THEN 'Gallery 3'
					WHEN 4 THEN 'Gallery 4'
					ELSE 'Gallery'
				END AS 'Type',
				g.gallery_id,
				g.gallery_title as 'Title',
				g.url as 'URL',
				g.category as 'Category',
				g.author_id,
				g.created as 'Date Created',
				u.username as 'Author',
				'' as 'Image'
			FROM
				gallery g
			LEFT JOIN
				username u
			ON
				g.user_id = u.id
		";
		$columns = array(
		    'Title',
		    'URL',
		    'Type',
		    'Category',
		    'Author',
		    'Image',
		    'Date Created'
		);
		$functions = array();
		$functions[] = array(
			'function'=>'editRow',
			'target'	=>'Image',
			'replace'	=>'<img src="www.mysite.com/{gallery_id}" alt="{Title}">',
			'params'=>array(
				'gallery_id',
				'Title'
			)
		);
		$searchable = array(
		    'Title',
		    'URL',
		    'Author',
		    'Category'
		);
		echo json_encode(
			SSP::buildTable($this->Model,$_REQUEST,$baseSql,$columns,$searchable,$functions)
		);
	}
}
