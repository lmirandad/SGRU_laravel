var start,end;
$( document ).ready(function(){	
	$("#input-file").fileinput({
	    language: "es",
	    allowedFileExtensions: ["csv"],
	    showPreview: false,
	    showUpload: false
	});

	

	$('#btnVistaPrevia').click(function(){
		var file = $('#input-file')[0].files;
		var config = buildConfig();
		start = performance.now();
		//alert(file[0].size);
		$('#input-file').parse({
			config: config,
			before: function(file, inputElem)
			{
				console.log("Parsing file:", file);
			},
			complete: function()
			{
				console.log("Done with all files.");
			}
		});
	});

});

function buildConfig(){
	return {
		delimiter:"|",
		newline: "\n",
		header: true,
		download: false,
		error: errorFn,
		complete: completeFn,
		
	};
}

function errorFn(error, file)
{
	console.log("ERROR:", error, file);
}

function completeFn()
{
	end = performance.now();
	console.log("Finished input (async). Time:", end-start, arguments);
}