$(document).ready(function(){
	var table = $('#galleries_table').DataTable({
		responsive: true,
		aLengthMenu: [[25, 50, 75, -1], [25, 50, 75, "All"]],
		iDisplayLength: 25,
		processing: true,
		serverSide: true,
		order: [[ 6, 'desc' ]],
		stateSave:true,
		columns:[
			{sTitle:"Title",data:"Title"},
			{sTitle:"URL",data:"URL"},
			{sTitle:"Type",data:"Type"},
			{sTitle:"Category",data:"Category"},
			{sTitle:"Author",data:"Author"},
			{sTitle:"Image",data:"Image"},
			{sTitle:"Date Created",data:"Date Created"}
		],
		ajax: {
			url:"/galleries_table",
			type:"POST"
		}
	});
});