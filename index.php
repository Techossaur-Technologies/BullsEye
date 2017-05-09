<!DOCTYPE html>
<html>
<head>
	<title>Bull's Eye</title>
</head>
<!-- Bootstrap styles -->
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<!-- Generic page styles -->
<link rel="stylesheet" href="plugins/jQuery_upload/css/style.css">
<!-- Jquery load mask -->
<link rel="stylesheet" href="plugins/jquery_loadmask/jquery.loadmask.css">
<!-- blueimp Gallery styles -->
<link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
<!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
<link rel="stylesheet" href="plugins/jQuery_upload/css/jquery.fileupload.css">
<link rel="stylesheet" href="plugins/jQuery_upload/css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="plugins/jQuery_upload/css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="plugins/jQuery_upload/css/jquery.fileupload-ui-noscript.css"></noscript>
<body>
	<form id="fileupload" action="//jquery-file-upload.appspot.com/" method="POST" enctype="multipart/form-data">
        <!-- Redirect browsers with JavaScript disabled to the origin page -->
        <noscript><input type="hidden" name="redirect" value="https://blueimp.github.io/jQuery-File-Upload/"></noscript>
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                    <input type="file" name="files[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>

    <!-- The blueimp Gallery widget -->
	<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
	    <div class="slides"></div>
	    <h3 class="title"></h3>
	    <a class="prev">‹</a>
	    <a class="next">›</a>
	    <a class="close">×</a>
	    <a class="play-pause"></a>
	    <ol class="indicator"></ol>
	</div>
	<!-- The template to display files available for upload -->
	<script id="template-upload" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-upload fade">
	        <td>
	            <span class="preview"></span>
	        </td>
	        <td>
	            <p class="name">{%=file.name%}</p>
	            <strong class="error text-danger"></strong>
	        </td>
	        <td>
	            <p class="size">Processing...</p>
	            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
	        </td>
	        <td>
	            {% if (!i && !o.options.autoUpload) { %}
	                <button class="btn btn-primary start" disabled>
	                    <i class="glyphicon glyphicon-upload"></i>
	                    <span>Start</span>
	                </button>
	            {% } %}
	            {% if (!i) { %}
	                <button class="btn btn-warning cancel">
	                    <i class="glyphicon glyphicon-ban-circle"></i>
	                    <span>Cancel</span>
	                </button>
	            {% } %}
	        </td>
	        <td>
	        	<label class="title">
				    <span>Title:</span><br>
				    <input name="title[]" class="form-control">
				</label>
				<label class="description">
				    <span>Description:</span><br>
				    <input name="description[]" class="form-control">
				</label>
	        </td>
	    </tr>
	{% } %}
	</script>
	<!-- The template to display files available for download -->
	<script id="template-download" type="text/x-tmpl">
	{% for (var i=0, file; file=o.files[i]; i++) { %}
	    <tr class="template-download fade">
	        <td>
	            <span class="preview">
	                {% if (file.thumbnailUrl) { %}
	                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
	                {% } %}
	            </span>
	        </td>
	        <td>
	            <p class="name">
	                {% if (file.url) { %}
	                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
	                {% } else { %}
	                    <span>{%=file.name%}</span>
	                {% } %}
	            </p>
	            {% if (file.error) { %}
	                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
	            {% } %}
	        </td>
	        <td>
	            <span class="size">{%=o.formatFileSize(file.size)%}</span>
	        </td>
	        <td>
	            {% if (file.deleteUrl) { %}
	                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
	                    <i class="glyphicon glyphicon-trash"></i>
	                    <span>Delete</span>
	                </button>
	                <input type="checkbox" name="delete" value="1" class="toggle">
	            {% } else { %}
	                <button class="btn btn-warning cancel">
	                    <i class="glyphicon glyphicon-ban-circle"></i>
	                    <span>Cancel</span>
	                </button>
	            {% } %}
	        </td>
	        <td>
	        	<p class="title"><strong>{%=file.title||''%}</strong></p>
				<p class="description">{%=file.description||''%}</p>
	        </td>
	    </tr>
	{% } %}
	</script>
	<input type="button" name="submit" id="process_file" class="btn btn-primary" value="Submit">
</body>

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="plugins/jQuery_upload/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="//blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Load-Image/js/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="//blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="//netdna.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="plugins/jQuery_upload/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="plugins/jQuery_upload/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="plugins/jQuery_upload/js/main.js"></script>
<script src="plugins/jquery_loadmask/jquery.loadmask.min.js"></script>
<script type="text/javascript">
	$('#fileupload').fileupload({
	    url: 'server/php/',
	    sequentialUploads: true,
	    maxRetries: 100,
	    retryTimeout: 500,
	    fail: function (e, data) {
	        // jQuery Widget Factory uses "namespace-widgetname" since version 1.10.0:
	        var fu = $(this).data('blueimp-fileupload') || $(this).data('fileupload'),
	            retries = data.context.data('retries') || 0,
	            retry = function () {
	                $.getJSON('server/php/', {file: data.files[0].name})
	                    .done(function (result) {
	                        var file = result.file;
	                        data.uploadedBytes = file && file.size;
	                        // clear the previous data:
	                        data.data = null;
	                        data.submit();
	                    })
	                    .fail(function () {
	                        fu._trigger('fail', e, data);
	                    });
	            };
	        if (data.errorThrown !== 'abort' &&
	                data.uploadedBytes < data.files[0].size &&
	                retries < fu.options.maxRetries) {
	            retries += 1;
	            data.context.data('retries', retries);
	            window.setTimeout(retry, retries * fu.options.retryTimeout);
	            return;
	        }
	        data.context.removeData('retries');
	        $.blueimp.fileupload.prototype
	            .options.fail.call(this, e, data);
	    }
	    // maxChunkSize: 6000000 // 10 MB
	}).on('fileuploadsubmit', function (e, data) {
	    data.formData = data.context.find(':input').serializeArray();
	});
	$('#process_file').on('click', function() {
		$.ajax({
           'type':'POST',
           'url':'ajax_pages/zip.php',
           'beforeSend': function(){ $('#fileupload').mask('Processing...'); },
           'async': false,
           'success':function(resp){
              console.log(resp);
               if(resp == 'success'){
                    alert('successfull');
                    $('#fileupload').unmask();
                } else{
                    $('#fileupload').unmask();
                    alert('unsuccessfull');
                }
           }
        });
	});
</script>
</html>