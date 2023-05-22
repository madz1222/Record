<div class="col-lg-12">
	<div class="card">
		<div class="card-body">
			<form action="" id="new_student_record">
			<input type="hidden" name="record_status" value="notyetapproved">
				<div class="row">
					<div class="col-md-6 border-right">
						<div class="form-group">
							<label for="" class="control-label">First Name</label>
							<input type="text" name="first_name" class="form-control form-control-sm" required value="<?php echo isset($first_name) ? $first_name : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Middle Name</label>
							<input type="text" name="middle_name" class="form-control form-control-sm"  value="<?php echo isset($middlename) ? $middlename : '' ?>">
						</div>
						<div class="form-group">
							<label for="" class="control-label">Last Name</label>
							<input type="text" name="last_name" class="form-control form-control-sm" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<div class="form-group">
							<label>Course</label>
							<input type="text" name="course_name" class="form-control" placeholder=""  value = "<?php if(isset($_POST['upload'])) {
								echo $course_name; } ?>" required="">
						</div>
						<div class="form-group">
							<label for="post_image">Select File</label>
							<span class="text-danger"><small> (allowed file type: 'pdf','doc','ppt','txt','zip' | allowed maximum size: 30 mb ) </small></span>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text" id="inputGroupFileAddon01">Upload</span>
								</div>
								<div class="custom-file">
									<input type="file" name="file" class="custom-file-input" id="inputGroupFile01"
									aria-describedby="inputGroupFileAddon01">
									<label class="custom-file-label" for="inputGroupFile01">Choose file</label>
								</div>
								</div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Year Entry</label><br>
							<label>From:</label>
								<select name="year_entry" class="form-control"   value = "<?php if(isset($_POST['upload'])) {
									echo $year_graduate; } ?>" required="">>
										<option value="1995">1995</option>
										<option value="1996">1996</option>
										<option value="1997">1997</option>
										<option value="1998">1998</option>
										<option value="1999">1999</option>
										<option value="2000">2000</option>
										<option value="2001">2001</option>
										<option value="2002">2002</option>
										<option value="2003">2003</option>
										<option value="2004">2004</option>
										<option value="2005">2005</option>
										<option value="2006">2006</option>
										<option value="2007">2007</option>
										<option value="2008">2008</option>
										<option value="2009">2009</option>
										<option value="2010">2010</option>
										<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2014">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
										<option value="2023">2023</option>
								</select>
						</div>
						<div class="form-group">
							<label> To:</label>
								<select name="year_graduate" class="form-control"   value = "<?php if(isset($_POST['upload'])) {
									echo $year_end; } ?>" required="">>
										<option value="1995">1995</option>
										<option value="1996">1996</option>
										<option value="1997">1997</option>
										<option value="1998">1998</option>
										<option value="1999">1999</option>
										<option value="2000">2000</option>
										<option value="2001">2001</option>
										<option value="2002">2002</option>
										<option value="2003">2003</option>
										<option value="2004">2004</option>
										<option value="2005">2005</option>
										<option value="2006">2006</option>
										<option value="2007">2007</option>
										<option value="2008">2008</option>
										<option value="2009">2009</option>
										<option value="2010">2010</option>
										<option value="2011">2011</option>
										<option value="2012">2012</option>
										<option value="2013">2013</option>
										<option value="2014">2014</option>
										<option value="2015">2015</option>
										<option value="2016">2016</option>
										<option value="2017">2017</option>
										<option value="2018">2018</option>
										<option value="2019">2019</option>
										<option value="2020">2020</option>
										<option value="2021">2021</option>
										<option value="2022">2022</option>
										<option value="2023">2023</option>
								</select>
						</div>
						<div class="form-group">
							<label>Graduated/Honorable Dismissed</label>
							<select name="grad_hd" class="form-control"   value = "<?php if(isset($_POST['upload'])) {
								echo $grad_hd; } ?>" required="">>
								<option value="Graduated">Graduated</option>
								<option value="Honorable Dismissed">Honorable Dismissed</option>
							</select>
						</div>
						
					</div>
				</div>
				<hr>
				<div class="col-lg-12 text-right justify-content-center d-flex">
					<button class="btn btn-success mr-2">Save</button>
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=record_list'">Cancel</button>
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
	$('#new_student_record').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')

		$.ajax({
			url:'ajax.php?action=new_student',
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
						location.href = 'index.php?page=record_list'
					},2000)
				}else if(resp == 2){
				$('#msg').html("<div class='alert alert-danger'>Detected duplicate entry for this record.</div>");
				$('[name="first_name"], [name="middle_name"], [name="last_name"]').addClass("border-danger");
				end_load();
			}
			}
		})
	})
</script>