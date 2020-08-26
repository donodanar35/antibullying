<!doctype html>
<html>
<head>
    <title>Multi Uploader</title>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script>
    $(document).ready(function(){
        
        $('#save').on('click', function(){
            var fileInput = $('#file_input')[0];
            var BASE_URL = "<?php echo base_url();?>";
            if( fileInput.files.length > 0 ){
                var formData = new FormData();
                $.each(fileInput.files, function(k,file){
                    formData.append('images[]', file);
                });
                $.ajax({
                    method: 'post',
                    url:BASE_URL + "coba/process",
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    success: function(response){
                        alert('response');
                    },
                    error: function (xhr, ajaxOptions, thrownError) { // Ketika terjadi error
                        alert(xhr.responseText) 
                    }
                });
            }else{
                console.log('No Files Selected');
            }
        });

    });
    </script>
</head>
<body>
    <form>
        <input type="file" name="images" id="file_input" multiple />
        <input type="text" name="nama" id="file_nama"/>
        <button type="button" id="save">Upload</button>
    </form>
</body>
</html>

