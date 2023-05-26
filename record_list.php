<div class="col-lg-12">
	<div class="card card-outline">
		<div class="card-header">	
			<?php if($_SESSION['login_type'] == 2 ): ?>	
			<div class="card-tools">
				<a class="btn btn-block btn-sm btn-success btn-flat" href="./index.php?page=new_student_record"><i class="fa fa-plus"></i> Add New</a>
			</div>		
			<?php endif; ?>
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
						<th>Action &nbsp;</th>
						<?php if($_SESSION['login_type'] == 1 ): ?>		
							<th>Clerk &nbsp;</th>
						<?php endif; ?>
						<?php if($_SESSION['login_type'] == 1 ): ?>	
							<th>Status&nbsp;</th>
						<?php endif; ?>
						<th>Last Name &nbsp;</th>
						<th>First Name &nbsp;</th>
						<th>Middle Name &nbsp;</th>
						<th>Full Name &nbsp;</th>
						<th>Course &nbsp;</th>
						<th>Year Entry &nbsp;</th>
						<th>Year Graduated &nbsp;</th>
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
						$where = " WHERE clerk_id = '{$_SESSION['login_id']}' AND record_status = 'approved' ";
					endif;
					$qry = $conn->query("SELECT * FROM record $where order by unix_timestamp(date_created) desc ");
					while($row = $qry->fetch_assoc()):
						$trans = get_html_translation_table(HTML_ENTITIES,ENT_QUOTES);
						unset($trans["\""], $trans["<"], $trans[">"], $trans["<h2"]);
					?>
					<tr>
							<td class="text-center">
								<div class="btn-group">
									<a href="./index.php?page=update_record&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-flat">
									<i class="fas fa-edit"></i>
									</a>
									<a href="./index.php?page=view_files&student_no=<?php echo $row['student_no'] ?>" class="btn btn-info btn-flat">
									<i class="fas fa-eye"></i>
									</a>
									<button type="button" class="btn btn-danger btn-flat delete_record" data-id="<?php echo $row['id']; ?>" data-student-no="<?php echo $row['student_no'];?>">
									<i class="fas fa-trash"></i>
									</button>
								</div>
							</td>
							<?php if ($_SESSION['login_type'] == 1): ?>
								<td class="clerk-id"><?php echo ucwords($row['clerk_id']) ?></td>
							<?php endif; ?>
							<?php if ($_SESSION['login_type'] == 1): ?>
								<td class="record-status" style="color:#5cb85c;">
								<b>
									<?php if ($row['record_status'] == 'notyetapproved'): ?>
										<div class="card-tools">
											<a class="btn btn-block btn-sm btn-success btn-flat update_status" data-id="<?php echo $row['id'] ?>" data-update-status="'approved'">
												<i class="fas fa-check" style="color: #ffffff;"></i>&nbsp;Approve
											</a>
										</div>
									<?php elseif ($row['record_status'] == 'approved'): ?>
										Approved
									<?php endif; ?>
									</b>	
								</td>
							<?php endif; ?>
							<td class="last-name"><?php echo ucwords($row['last_name']) ?></td>
							<td class="first-name"><?php echo ucwords($row['first_name']) ?></td>
							<td class="middle-name"><?php echo ucwords($row['middle_name']) ?></td>
							<td class="full-name">
								<?php echo ucwords($row['last_name']) ?>, <?php echo ucwords($row['first_name']) ?>, <?php echo ucwords($row['middle_name']) ?>
							</td>
							<td class="course-name"><?php echo ucwords($row['course_name']) ?></td>
							<td class="year-entry"><?php echo ucwords($row['year_entry']) ?></td>
							<td class="year-graduate"><?php echo ucwords($row['year_graduate']) ?></td>
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
		$('#list').on('click', '.delete_record', function() {
        	_conf("Are you sure to delete this record? <br><br><small> Linked files will also be deleted</small>", "delete_record", [$(this).data('id'), $(this).data('student-no')]);
		});
		$('.update_status').click(function(){
        	_conf("Are you sure to approve this record?", "update_status", [$(this).data('id'), $(this).data('update-status')]);
		});
	});
	function delete_record($id, $student_no){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_record',
			method:'POST',
			data:{id:$id, student_no:$student_no},
			success:function(resp){
				if(resp==1){
					alert_toast("Record Successfully Deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)

				}
			}
		})
	}
	function update_status($id, $update_status){
		start_load()
		$.ajax({
			url:'ajax.php?action=update_status',
			method:'POST',
			data:{id:$id, update_status:$update_status},
			success:function(resp){
				if(resp==1){
					alert_toast("Status Successfully Updated",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>