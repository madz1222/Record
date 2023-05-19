<?php
session_start();
ini_set('display_errors', 1);

include 'db_connect.php';

class Action {
    private $db;

    public function __construct($conn) {
        ob_start();
        $this->db = $conn;
    }

    function __destruct() {
        $this->db->close();
        ob_end_flush();
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
    
    // Add a new method to call the generateStudentNo() function and echo the return value
    function callGenerateStudentNo() {
        // Call the function
        $returnValue = $this->generateStudentNo();

        // Echo the return variable
        echo $returnValue;
    }

    function new_file() {
		extract($_POST);
		
		$student_no = $student_no;
		$clerk_id = $_SESSION['login_id'];
		$valid_ext = array ('pdf', 'txt', 'doc', 'docx', 'ppt' , 'zip');
		$file_name = $_FILES['file']['name'];
		$file_type = $_FILES['file']['type'];
		$file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
		$file_temp = $_FILES['file']['tmp_name'];
		$location = "userfiles/".$student_no."/".$file_name;
		$date = date("Y-m-d, h:i A", strtotime("+6 HOURS"));


        return "'$student_no', $_SESSION[login_id], '$file_name', '$file_ext', '$date', '$date'";
    }
    function callfileke() {
        // Call the function
        $filekeme = $this->new_file();

        // Echo the return variable
        echo $filekeme;
    }
}

// Create an instance of the class and pass the $conn variable
$action = new Action($conn);

// Call the method to generate and echo the student number
$action->callGenerateStudentNo();
$action->callfileke();
?>
