<?php $student_no = $_GET['student_no']; ?>
<div class="col-md-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="new_file">
				<input type="hidden" name="student_no" value="<?php echo isset($student_no) ? $student_no : '' ?>">
				<div class="row">
					<div class="col-md-12" id="fileinputcont">
                        <div class="form-group">
                        <label for="inputGroupFile01">Select File</label>
                        <span class="text-danger"><small> (allowed file type: 'pdf', 'doc', 'ppt', 'txt', 'zip' | allowed maximum size: 30 mb)</small></span>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
                                </div>
                                <div class="custom-file">
                                <input type="file" name="file" class="custom-file-input" id="inputGroupFile01"
                                        aria-describedby="inputGroupFileAddon01" onchange="handleFileSelect(this)">
                                <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                </div>
                            </div>
                        </div>
					</div>
				</div>
                <?php
                if($_SESSION['login_type'] == 1 ):
					$user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
					while($row = $user->fetch_assoc()){
						$uname[$row['id']] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
					}
					else:
						$where = " where clerk_id = '{$_SESSION['login_id']}' ";
					endif;

                    ?>

				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-primary mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = './index.php?page=view_files&student_no=<?php echo $_GET['student_no'] ?>'">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>
<style>
	img#cimg{
		max-height: 15vh;
		/*max-width: 6vw;*/
	}
</style>
<script>
	/*
    function handleFileSelect(input) {
    var files = input.files;
    var fileInputsContainer = document.getElementById("fileinputcont");
    var existingInputGroup = input.parentElement.parentElement.parentElement;

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var formGroupDiv = document.createElement("div");
        formGroupDiv.className = "form-group";
        var newInputId = "inputGroupFile" + Date.now(); // Generate a unique ID for each new input
        formGroupDiv.innerHTML = `
        <label for="${newInputId}">Select File</label>
        <span class="text-danger"><small> (allowed file type: 'pdf', 'doc', 'ppt', 'txt', 'zip' | allowed maximum size: 30 mb)</small></span>
        <div class="input-group">
            <div class="input-group-prepend">
            <span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
            </div>
            <div class="custom-file">
            <input type="file" name="file" class="custom-file-input" id="${newInputId}"
                    aria-describedby="inputGroupFileAddon01" onchange="handleFileSelect(this)">
            <label class="custom-file-label" for="${newInputId}">${file.name}</label>
            </div>
        </div>
        `;
        // Insert the new input field on top of the existing input field
        fileInputsContainer.insertBefore(formGroupDiv, existingInputGroup);
    }

    // Clear the value of the original file input
    input.value = "";
    }
*/
	$('#new_file').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=new_file',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('File Uploaded',"success");
					setTimeout(function(){
						location.href = './index.php?page=view_files&student_no=<?php echo $_GET['student_no'] ?>'
					},2000)
				}
			}
		})
	})
</script>