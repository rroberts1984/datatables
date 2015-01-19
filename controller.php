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
		//List all of your columns here
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
		//adding functions is not required. This is just a way to run functions on the data if you wish.
		//This will take the target which is the Image row from the query, and change it to the "replace" string.
		//You can use part of the query to build the replace using {} and passing them in the params.
		$functions[] = array(
			'function'=>'editRow',
			'target'	=>'Image',
			'replace'	=>'<img src="www.mysite.com/{gallery_id}" alt="{Title}">',
			'params'=>array(
				'gallery_id',
				'Title'
			)
		);
		//list any columns you want to be "searchable".
		//you can also list non visable data here to be searchable, such as the gallery_id.
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
