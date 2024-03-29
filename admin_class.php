<?php
session_start();
ini_set('display_errors', 1);

require 'assets/plugins/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

Class Action {
	private $db;

	public function __construct() {
		ob_start();
   	include 'db_connect.php';
    
    $this->db = $conn;
	}
	function __destruct() {
	    $this->db->close();
	    ob_end_flush();
	}
	
	function login() {
		extract($_POST);
		$stmt = $this->db->prepare("SELECT *, CONCAT(lastname, ', ', firstname, ' ', middlename) AS name FROM users WHERE email = ?");
		$stmt->bind_param("s", $email);
		$stmt->execute();
		$result = $stmt->get_result();
	
		if ($result->num_rows > 0) {
			$user = $result->fetch_assoc();
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

		$check = $this->db->query("SELECT * FROM record WHERE LOWER(first_name) = LOWER('$first_name') AND LOWER(last_name) = LOWER('$last_name') AND LOWER(middle_name) = LOWER('$middle_name') ".(!empty($id) ? " AND id != {$id} " : ''))->num_rows;
		if($check > 0){
			return 2;
			exit;
		}

	
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
			
			$stmt = $this->db->prepare("INSERT INTO user_file (student_no, clerk_id, file_name, file_type, date_uploaded, file_owner) 
				VALUES (?, ?, ?, ?, ?, ?)");
			$stmt->bind_param("iissss", $student_no, $_SESSION['login_id'], $file_name, $file_ext, $date, $date);
			$stmt->execute();
		}   
	
		$stmt = $this->db->prepare("INSERT INTO record (student_no, clerk_id, first_name, last_name, middle_name, course_name, year_graduate, year_entry, grad_hd, record_status) 
			VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("iissssssss", $student_no, $_SESSION['login_id'], $first_name_formatted, $last_name_formatted, $middle_name_formatted, $course_name, $year_graduate, $year_entry, $grad_hd, $record_status);
		$stmt->execute();
	
		if ($stmt) {
			return 1; // Success
		} else {
			return 0; // Error
		}
	}
	function import_excel() {
        extract($_POST);
        $clerk_id = 3;
        $location = "userfiles/";
    
        if (isset($_FILES['importfile']['tmp_name'])) {
            $file = $_FILES['importfile']['tmp_name'];
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = [];
    
            // Define the column indexes for each field
            $columnIndexes = [
                'First_Name' => 'A',
                'Middle_Name' => 'B',
                'Last_Name' => 'C',
                'Course_Name' => 'D',
                'Year_Entry' => 'E',
                'Year_Graduated' => 'F',
                'Grad_HD' => 'G'
            ];
    
            // Get the starting row and column indexes
            $startRow = 3;  // Start reading from row 3
            $startColumn = 'A';  // Start reading from column A
    
            // Process the data from the Excel file
            for ($rowIndex = $startRow;; $rowIndex++) {
                $rowData = [];
    
                foreach ($columnIndexes as $field => $column) {
                    $cellValue = $worksheet->getCell($column . $rowIndex)->getValue();
                    $rowData[$field] = $cellValue;
                }
    
                // Check if all the values are empty, indicating the end of the data
                if (empty(array_filter($rowData))) {
                    break;
                }
    
                $data[] = $rowData;
            }
    
            // Process each row of data
            foreach ($data as $row) {
                $student_no = (int) $this->generateStudentNo(); // Generate a unique student_no for each row
                $first_name = $row['First_Name'];
                $middle_name = $row['Middle_Name'];
                $last_name = $row['Last_Name'];
                $course_name = $row['Course_Name'];
                $year_entry = $row['Year_Entry'];
                $year_graduated = $row['Year_Graduated'];
                $grad_hd = $row['Grad_HD'];
    
                $last_name_words = explode(' ', $last_name);
                $first_name_words = explode(' ', $first_name);
                $middle_name_words = explode(' ', $middle_name);
    
                $last_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $last_name_words));
    
                $first_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $first_name_words));
    
                $middle_name_formatted = implode(' ', array_map(function ($word) {
                    return ucfirst(strtolower($word));
                }, $middle_name_words));
    
                $record_status = 'approved'; // Add the appropriate value for record_status
    
                $stmt = $this->db->prepare("INSERT INTO record (student_no, clerk_id, first_name, last_name, middle_name, course_name, year_graduate, year_entry, grad_hd, record_status) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssssss", $student_no, $_SESSION['login_id'], $first_name_formatted, $last_name_formatted, $middle_name_formatted, $course_name, $year_graduated, $year_entry, $grad_hd, $record_status);
                $stmt->execute();
    
                if ($stmt) {
                    // Create a unique directory for each student based on student_no
                    $student_location = $location . $student_no;
                    if (!file_exists($student_location)) {
                        mkdir($student_location);
                    }
                }
            }
    
            return 1; // Success
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
	
	function delete_file() {
		extract($_POST);
	
		// Remove the single quotes from the file name
		$file_name = str_replace("'", '', $file_name);
	
		$location = "userfiles/" . $student_no . "/" . $file_name;
	
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
		extract($_POST);
	
		$update = $this->db->query("UPDATE record SET 
		id = '$id', 
		first_name = '$first_name', 
		last_name = '$last_name', 
		middle_name = '$middle_name', 
		course_name = '$course_name', 
		year_graduate = '$year_graduate', 
		year_entry = '$year_entry', 
		grad_hd = '$grad_hd' 
		WHERE id = '$id'");

		if ($update) {
		return 1;
		}

	}
	
	function delete_record() {
		extract($_POST);
		$deleteRecord = $this->db->query("DELETE FROM record WHERE id = " . $id);
		$deleteUserFile = $this->db->query("DELETE FROM user_file WHERE student_no = " . $student_no);
	
		$location = "userfiles/" . $student_no;
	
		if (is_dir($location)) {
			// Remove all files and subdirectories within the directory
			$files = glob($location . '/*');
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
	
			// Delete the directory itself
			rmdir($location);
		}
		
		if ($deleteRecord && $deleteUserFile) {
			return 1;
		}
	}	
	
	function approve_record() {
		extract($_POST);
	
		$update = $this->db->query("UPDATE record SET 
		record_status = '$approve_record'
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
