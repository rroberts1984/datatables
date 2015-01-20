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
		/*
		 * adding functions is not required. This is just a way to run functions on the data if you wish.
		 * This will take the target which is the Image row from the query, and change it to the "replace" string.
		 * You can use part of the query to build the replace using {} and passing them in the params.
		*/
		$functions[] = array(
			'function'=>'editRow',
			'target'	=>'Image',
			'replace'	=>'<img src="www.mysite.com/{gallery_id}" alt="{Title}">',
			'params'=>array(
				'gallery_id',
				'Title'
			)
		);
		/*
		 * Add this to change the date format. Simply put in the date format
		 * of your column in the format_from value, and put the
		 * desired date format in the format_to value
		 * 20150101 becomes Thu: 01-01-15
		*/
		$functions[] = array(
		    'function'=>'date_format',
		    'format_to'=>'D: m-d-y',
		    'format_from'=>'Ymd',
		    'columns'=>array(
			    'Date Created'
		    )
		);
		/*
		 * list any columns you want to be "searchable".
		 * you can also list non visable data here to be searchable, such as the gallery_id.
		*/
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
	
	/*
	 * More functions that are not used in the above example:
	*/
	function moreFunctionExamples(){
		/*
		 * This will add commas and handle a decimal, or add one to a number.
		 * Prefix and suffix can be left blank, or be used to add a string to
		 * the beginning or end of the number. A typical case is just to add
		 * a dollar sign. I've never needed a suffix, but both are there just in case.
		*/
		$functions[] = array(
			'function'=>'number_format_decimal',
			'prefix'=>'$',
			'suffix'=>'',
			'columns'=>array(
				'Earned',
				'Expense'
			)
		);
		/*
		 * Same as the above but no decimal
		*/
		$functions[] = array(
			'function'=>'number_format_whole',
			'prefix'=>'We still have $',
			'suffix'=>' to go!',
			'columns'=>array(
				'Total Funds Needed'
			)
		);
	}
}
