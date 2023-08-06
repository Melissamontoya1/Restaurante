<?php

include("functions.php");
session_start();
if (isset($_POST['login']) ) {	
	$username = $sqlconnection->real_escape_string($_POST['user_name']);
	$password = $sqlconnection->real_escape_string($_POST['user_password']);

	$sql = "SELECT * FROM tbl_staff WHERE username ='$username' AND password = '$password'";

	if ($result = $sqlconnection->query($sql)) {

		if ($row = $result->fetch_array(MYSQLI_ASSOC)) {

			$uid = $row['staffID'];
			$username = $row['username'];
			$role = $row['role'];

			$_SESSION['uid'] = $uid;
			$_SESSION['username'] = $username;
                $_SESSION['user_level'] = "staff"; // 1 - admin 2 - staff
                $_SESSION['user_role'] = $role;

                header('location: admin');
                echo "correct";
            }else{

            	$sql = "SELECT * FROM tbl_admin WHERE username ='$username' AND password = '$password'";

            	if ($result = $sqlconnection->query($sql)) {

            		if ($row = $result->fetch_array(MYSQLI_ASSOC)) {

            			$uid = $row['ID'];
            			$username = $row['username'];

            			$_SESSION['uid'] = $uid;
            			$_SESSION['username'] = $username;
            			$_SESSION['user_level'] = "admin";

            			header('location: admin');
            		}else {
            			echo "<script>alert('usuario / contraseña invalida');
            			window.location.href= 'index.php';</script>";
            		}
            	}
            }

        }
    }else {
    	header('location: index.php');
    }

?>