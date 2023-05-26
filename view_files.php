<?php $student_no = $_GET['student_no']; ?>
<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">
			<div class="card-tools ml-4">
				<a class="btn btn-block btn-sm btn-secondary btn-flat" href="./index.php?page=record_list"><i class="bi bi-chevron-left"></i> Back </a>
			</div>
            <div class="card-tools">
				<a class="btn btn-block btn-sm btn-success btn-flat" href="./index.php?page=new_file&student_no=<?php echo $_GET['student_no'] ?>"><i class="fa fa-plus"></i> Add New</a>
			</div>
		</div>
		
		<div class="card-body">
			<table class="table table-hover table-bordered table-sm display nowrap" style="width:100%" id="list">
			     <?php if($_SESSION['login_type'] == 1 ): ?>
				<colgroup>
					<col width="10%">
					<col width="25%">
					<col width="35%">
					<col width="20%">
					<col width="10%">
				</colgroup>
			    <?php else: ?>
				<colgroup>
					<col width="10%">
					<col width="30%">
					<col width="50%">
					<col width="10%">
				</colgroup>
			    <?php endif; ?>

				<thead>
					<tr>
						<th class="text-center">File # </th>
						<th>File Name &nbsp;</th>
						<th>File Type &nbsp;</th>
						<th>Date Uploaded &nbsp;</th>
						<th>Full Name &nbsp;</th>
						<th>Action &nbsp;</th>
					    <?php if($_SESSION['login_type'] == 1 ): ?>
						<?php endif; ?>
					</tr>
				</thead>
				<tbody>
					<?php
					$i = 1;
					$where = '';
					if($_SESSION['login_type'] == 1 ):
					$user = $conn->query("SELECT * FROM users where id in (SELECT clerk_id FROM record) ");
					while($row = $user->fetch_assoc()){
						$uname[$row['id']] = ucwords($row['lastname'].', '.$row['firstname'].' '.$row['middlename']);
					}
					else:
						$where = " where clerk_id = '{$_SESSION['login_id']}' ";
					endif;

					$query1 = mysqli_query($conn, "SELECT * FROM user_file WHERE student_no = '$student_no'");
					while($user_file = mysqli_fetch_array($query1)):

					?>
					<tr>
						<th class="text-center"><?php echo $user_file['file_id'] ?></th>
						<td><?php echo ucwords(substr($user_file['file_name'], strpos($user_file['file_name'], '_') + 1)) ?></td>
						<td><?php echo ucwords($user_file['file_type']) ?></td>
						<td><?php echo ucwords($user_file['date_uploaded']) ?></td>
						<td><?php echo ucwords($user_file['file_owner']) ?></td>
						<td class="text-center">
		                    <div class="btn-group">
								<a href="<?php echo 'userfiles/' . $user_file['student_no'] . '/' . $user_file['file_name']; ?>" target="_blank" class="btn btn-info btn-flat">
									<i class="fas fa-eye"></i>
								</a>
								<button type="button" class="btn btn-danger btn-flat delete_file" data-file-id="<?php echo $user_file['file_id']; ?>" data-student-no="<?php echo $user_file['student_no'];?>" data-file-name="<?php echo $user_file['file_name'];?>">
		                          	<i class="fas fa-trash"></i>
		                        </button>
	                        </div>
						</td>
                        </tr>	
                    <?php endwhile; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    $('#list').dataTable({
        scrollX: true,
    });
    $('#list').on('click', '.delete_file', function(){
        var file_id = $(this).data('file-id');
        var student_no = $(this).data('student-no');
        var file_name = $(this).data('file-name');
        console.log(file_id, student_no, file_name); // Log the parameter values

        _conf("Are you sure to delete this file?", "delete_file", [file_id, student_no, "'" + file_name + "'"]);
    });
});

function delete_file(file_id, student_no, file_name) {
    start_load();
    $.ajax({
        url: 'ajax.php?action=delete_file',
        method: 'POST',
        data: {file_id: file_id, student_no: student_no, file_name: "'" + file_name.replace(/'/g, "\\'") + "'"}, // Escape single quotes in file_name
        success: function(resp) {
            if (resp == 1) {
                alert_toast("File Successfully Deleted", 'success');
                setTimeout(function() {
                    location.reload();
                }, 1500);
            }
        }
    });
}


</script>

