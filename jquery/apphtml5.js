function bytesToSize(bytes) {
	var sizes = ['Bytes', 'KB', 'MB'];
	if (bytes == 0) return 'n/a';
	var i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)));
	return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
};

function send_form_loadinghtml5(namaform, action) {
	//$("#reportFormSubmit" ).remove();
	
	//$('#'+namaform).append("<div id='reportFormSubmit' style='text-align:center; width:100%; padding:10px; background-color:#EFC0C1; border:#9A9A9A thin solid; border-radius:2px; margin:auto'></div>"); 
	
	var vFD = new FormData(document.getElementById(namaform)); 

	// create XMLHttpRequest object, adding few event listeners, and POSTing our data
	var oXHR = new XMLHttpRequest();        
	oXHR.upload.addEventListener('progress', uploadProgress, false);
	oXHR.addEventListener('load', uploadFinish, false);
	//oXHR.addEventListener('error', uploadError, false);
	//oXHR.addEventListener('abort', uploadAbort, false);
	oXHR.open('POST', site+action);
	oXHR.send(vFD);

}


function uploadProgress(e) { // upload process in progress
	if (e.lengthComputable) {
		iBytesUploaded = e.loaded;
		iBytesTotal = e.total;
		var iPercentComplete = Math.round(e.loaded * 100 / e.total);
		var iBytesTransfered = bytesToSize(iBytesUploaded);

		document.getElementById('reportFormSubmit').innerHTML = iPercentComplete.toString() + '%';
		if (iPercentComplete == 100) {
			var oUploadResponse = document.getElementById('reportFormSubmit');
			oUploadResponse.innerHTML = '<p>... finishing</p>';
			oUploadResponse.style.display = 'block';
		}
	} else {
		document.getElementById('reportFormSubmit').innerHTML = 'unable to compute';
	}
}

function uploadFinish(e) { // upload successfully finished
	$('#script').html(e.target.responseText);
}

function uploadError(e) { // upload error
	$('#reportFormSubmit').html('upload error');
}  

function uploadAbort(e) { // upload abort
	$('#reportFormSubmit').html('upload aborted');
}

function PrintElem(elem){
	PopupElem($(elem).html());
}

function PopupElem(data){
	var mywindow = window.open('', 'new div', 'height=400,width=600');
	mywindow.document.write('<html><head><title>Cetak halaman</title>');
	/*optional stylesheet*/ //mywindow.document.write('<link rel="stylesheet" href="main.css" type="text/css" />');
	mywindow.document.write('</head><body >');
	mywindow.document.write(data);
	mywindow.document.write('</body></html>');

	mywindow.print();
	mywindow.close();

	return true;
}



