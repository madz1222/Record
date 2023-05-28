<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form method="" enctype="multipart/form-data" action="" id="import_excel">
				<div class="row">
					<div class="col-md-12" id="fileinputcont">
						<div class="form-group">
							<label for="inputGroupFile01">Select Excel File:</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
								</div>
								<div class="custom-file">
									<input type="file" name="importfile" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
									<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button type="submit" name="submit" class="btn btn-primary mr-2">Import</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = './index.php?page=record_list'">Cancel</button>
				</div>
				</div>
			</form>
		</div>
	</div>
</div>
</body>
</html>
<script>
    $('#import_excel').submit(function(e){
    e.preventDefault()
    $('input').removeClass("border-danger")
    start_load()
    $('#msg').html('')

    $.ajax({
        url:'ajax.php?action=import_excel',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success:function(resp){
            if(resp == 1){
                alert_toast('Record Successfully Uploaded',"success");
                setTimeout(function(){
                    location.href = './index.php?page=record_list'
                },2000)
            }
        }
    })
})
</script>
