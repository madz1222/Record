<?php
session_start();
ini_set('display_errors', 1);
Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
	include 'phpmailer.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}

	function login() {
		extract($_POST);
		$qry = $this->db->query("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM users WHERE email = '".$email."'");
		
		if ($qry->num_rows > 0) {
			$user = $qry->fetch_assoc();
			if (password_verify($password, $user['password'])) {
				foreach ($user as $key => $value) {
					if ($key != 'password' && !is_numeric($key))
						$_SESSION['login_'.$key] = $value;
				}
				return 1; // Login successful
			}
		}
		
		return 3; // Invalid credentials
	}
	function logout(){
		session_destroy();
		foreach ($_SESSION as $key => $value) {
			unset($_SESSION[$key]);
		}
		header("location:login.php");
	}
	function generateStudentNo() {
        $query = "SELECT MIN(student_no + 1) AS student_no
            FROM (
              SELECT student_no FROM record
              UNION ALL
              SELECT student_no FROM user_file
            ) AS combined
            WHERE (student_no + 1) NOT IN (
              SELECT student_no FROM record
              UNION ALL
              SELECT student_no FROM user_file
            );";
        $result = mysqli_query($this->db, $query);
        $arrstudent_no = mysqli_fetch_assoc($result);
    
        $student_no = $arrstudent_no['student_no']; // Assign the value to the variable
    
        return $student_no;
    }
	function new_student() {
		extract($_POST);
	
		$student_no = (int) $this->generateStudentNo();
		$clerk_id = $_SESSION['login_id'];
		$valid_ext = array('pdf', 'txt', 'doc', 'docx', 'ppt', 'zip');
		$file_name = strtotime(date('y-m-d H:i:s')).'_'.$_FILES['file']['name']; // Add timestamp with seconds
		$file_type = $_FILES['file']['type'];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_temp = $_FILES['file']['tmp_name'];
		$location = "userfiles/".$student_no."/".$file_name;
		$date = date("Y-m-d, h:i A", strtotime("+6 HOURS"));
	
		if (!file_exists("userfiles/".$student_no)) {
			mkdir("userfiles/".$student_no);
		}
	
		$last_name_words = explode(' ', $last_name);
		$first_name_words = explode(' ', $first_name);
		$middle_name_words = explode(' ', $middle_name);
	
		$last_name_formatted = implode(' ', array_map(function($word) {
			return ucfirst(strtolower($word));
		}, $last_name_words));
	
		$first_name_formatted = implode(' ', array_map(function($word) {
			return ucfirst(strtolower($word));
		}, $first_name_words));
	
		$middle_name_formatted = implode(' ', array_map(function($word) {
			return ucfirst(strtolower($word));
		}, $middle_name_words));
	
		if (!empty($file_ext)) {
			move_uploaded_file($file_temp, $location);
			
			$insertfile = $this->db->query("INSERT INTO user_file (student_no, clerk_id, file_name, file_type, date_uploaded, file_owner) 
				VALUES ('$student_no', $_SESSION[login_id], '$file_name', '$file_ext', '$date', '$date')");
		}		
	
		$insert = $this->db->query("INSERT INTO record (student_no, clerk_id, first_name, last_name, middle_name, course_name, year_graduate, year_entry, grad_hd, record_status) 
			VALUES ('$student_no', $_SESSION[login_id], '$first_name_formatted', '$last_name_formatted', '$middle_name_formatted', '$course_name', '$year_graduate', '$year_entry', '$grad_hd', '$record_status')");
	
		if ($insert) {
			return 1;
		}
	}	
	function save_user(){
		extract($_POST);
		$data = "";
		foreach($_POST as $k => $v){
			if(!in_array($k, array('id','cpass')) && !is_numeric($k)){
				if($k =='password')
					$v = password_hash($v, PASSWORD_DEFAULT);
				if(empty($data)){
					$data .= " $k='$v' ";
				}else{
					$data .= ", $k='$v' ";
				}
			}
		}
	
		$check = $this->db->query("SELECT * FROM users where email ='$email' ".(!empty($id) ? " and id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}
		if($_FILES['img']['tmp_name'] != ''){
			$fname = time().'_'.$_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'],'assets/uploads/'. $fname);
			$data .= ", avatar = '$fname' ";
		}
	
		if(empty($id)){
			$save = $this->db->query("INSERT INTO users SET $data");
		}else{
			$save = $this->db->query("UPDATE users SET $data WHERE id = $id");
		}
	
		if($save){
			return 1;
		}
	}
	
	function update_user() {
		extract($_POST);
		$data = "";
		foreach ($_POST as $k => $v) {
			if (!in_array($k, array('id', 'cpass', 'table')) && !is_numeric($k) && $v !== '') {
				if ($k == 'password') {
					$v = password_hash($v, PASSWORD_DEFAULT);
				}
				if (empty($data)) {
					$data .= " $k='$v' ";
				} else {
					$data .= ", $k='$v' ";
				}
			}
		}
		if ($_FILES['img']['tmp_name'] != '') {
			$fname = time() . '_' . $_FILES['img']['name'];
			$move = move_uploaded_file($_FILES['img']['tmp_name'], 'assets/uploads/' . $fname);
			$data .= ", avatar = '$fname' ";
		}
		$check = $this->db->query("SELECT * FROM users WHERE email ='$email'" . (!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if ($check > 0) {
			return 2; // Email already exists
		}
		if (empty($id)) {
			$save = $this->db->query("INSERT INTO users SET $data");
		} else {
			$save = $this->db->query("UPDATE users SET $data WHERE id = $id");
		}
		if ($save) {
			foreach ($_POST as $key => $value) {
				if ($key != 'password' && !is_numeric($key)) {
					$_SESSION['login_' . $key] = $value;
				}
			}
			if ($_FILES['img']['tmp_name'] != '') {
				$_SESSION['login_avatar'] = $fname;
			}
			return 1; // Success
		}
	}
	
	function delete_user(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM users where id = ".$id);
		if($delete)
			return 1;
	}

	function upload_file(){
		extract($_FILES['file']);
		// var_dump($_FILES);
		if($tmp_name != ''){
				$fname = strtotime(date('y-m-d H:i')).'_'.$name;
				$move = move_uploaded_file($tmp_name,'assets/uploads/'. $fname);
		}
		if(isset($move) && $move){
			return json_encode(array("status"=>1,"fname"=>$fname));
		}
	}//Not Used -Robell

	function remove_file(){
		extract($_POST);
		if(is_file('assets/uploads/'.$fname))
			unlink('assets/uploads/'.$fname);
		return 1;
	}//Not Used -Robell
	
	function delete_record(){
		extract($_POST);
		$delete = $this->db->query("DELETE FROM record where id = ".$id);
		$delete = $this->db->query("DELETE FROM user_file where student_no = " . $student_no);
		
		if($delete){
			return 1;
		}
	}
	function delete_file(){
		extract($_POST);

		$location = 'userfiles/' . $student_no . '/' . $file_name;

		if (is_file($location)) {
			unlink($location);
		}
		
		$delete = $this->db->query("DELETE FROM user_file WHERE file_id = " . $file_id);
		if ($delete) {
			return 1; 
		}
	}
	function save_upload(){
		extract($_POST);
		// var_dump($_FILES);
		$data = " title ='$title' ";
		$data .= ", description ='".htmlentities(str_replace("'","&#x2019;",$description))."' ";
		$data .= ", clerk_id ='{$_SESSION['login_id']}' ";
		$data .= ", file_json ='".json_encode($fname)."' ";
		if(empty($id)){
			$save = $this->db->query("INSERT INTO record set $data ");
		}else{
			$save = $this->db->query("UPDATE record set $data where id = $id");
		}
		if($save){
			return 1;
		}
	}//Not Used -Robell

	function update_record() {
		$id = $_POST['id'] ?? '';
		$first_name = $_POST['first_name'] ?? '';
		$last_name = $_POST['last_name'] ?? '';
		$middle_name = $_POST['middle_name'] ?? '';
		$course_name = $_POST['course_name'] ?? '';
		$year_graduate = $_POST['year_graduate'] ?? '';
		$year_entry = $_POST['year_entry'] ?? '';
		$grad_hd = $_POST['grad_hd'] ?? '';
	
		$updateQuery = "UPDATE record SET ";
		$updateColumns = array();
		$params = array();
	
		if (!empty($id)) {
			$updateColumns[] = "id = ?";
			$params[] = $id;
		}
		if (!empty($first_name)) {
			$updateColumns[] = "first_name = ?";
			$params[] = $first_name;
		}
		if (!empty($last_name)) {
			$updateColumns[] = "last_name = ?";
			$params[] = $last_name;
		}
		if (!empty($middle_name)) {
			$updateColumns[] = "middle_name = ?";
			$params[] = $middle_name;
		}
		if (!empty($course_name)) {
			$updateColumns[] = "course_name = ?";
			$params[] = $course_name;
		}
		if (!empty($year_graduate)) {
			$updateColumns[] = "year_graduate = ?";
			$params[] = $year_graduate;
		}
		if (!empty($year_entry)) {
			$updateColumns[] = "year_entry = ?";
			$params[] = $year_entry;
		}
		if (!empty($grad_hd)) {
			$updateColumns[] = "grad_hd = ?";
			$params[] = $grad_hd;
		}
	
		if (!empty($updateColumns)) {
			$updateQuery .= implode(", ", $updateColumns);
			$updateQuery .= " WHERE id = ?";
			$params[] = $id;
	
			$stmt = $this->db->prepare($updateQuery);
			if ($stmt) {
				$stmt->execute($params);
				return 1;
			}
		}
	}
	
	function update_status() {
		extract($_POST);
	
		$update = $this->db->query("UPDATE record SET 
		record_status = '$update_status'
		WHERE id = '$id'");

		if ($update) {
		return 1;
		}

	}
	function reset_password() {
		extract($_POST);
	
		$reset = $this->db->query("UPDATE users SET
		token = '$token'
		WHERE email = '$email'");
		if ($reset) {
			return 1;
		}
		
	}
	
	function new_file() {
		extract($_POST);
	
		$clerk_id = $_SESSION['login_id'];
		$valid_ext = array('pdf', 'txt', 'doc', 'docx', 'ppt', 'zip');
		$file_name = strtotime(date('Y-m-d H:i:s')).'_'.$_FILES['file']['name']; // Add timestamp with seconds
		$file_type = $_FILES['file']['type'];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_temp = $_FILES['file']['tmp_name'];
		$location = "userfiles/" . $student_no . "/" . $file_name;
		$date = date("Y-m-d, h:i A", strtotime("+6 HOURS"));
	
		if (!file_exists("userfiles/" . $student_no)) {
			mkdir("userfiles/" . $student_no);
		}
	
		move_uploaded_file($file_temp, $location);
	
		$insertfile = $this->db->query("INSERT INTO user_file (student_no, clerk_id, file_name, file_type, date_uploaded, file_owner) 
			VALUES ('$student_no', $_SESSION[login_id], '$file_name', '$file_ext', '$date', '$date')");
	
		if ($insertfile) {
			return 1;
		}
	}
}
