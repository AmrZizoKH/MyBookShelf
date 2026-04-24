<?php

namespace App;
use \App\Database;
use \App\Warnings;
class Authenticate
{
    public function signup()
    {
        if (isset($_POST['signUpBtn'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $hashPassword = password_hash($password, PASSWORD_DEFAULT);
            $confirmpassword = $_POST['confirm_password'];
            if ($password != $confirmpassword) {
                Warnings::printMessage("Passwords Don't Match", "danger");
            } else {

                $databaseObj = new \App\Database();
                $checkQuery = "SELECT user_id FROM `userdata` WHERE email = ? LIMIT 1";
                $checkStmt = $databaseObj->connectdb->prepare($checkQuery);
                $checkStmt->bind_param("s", $email);
                $checkStmt->execute();
                $existingUser = $checkStmt->get_result();

                if ($existingUser->num_rows > 0) {
                    Warnings::printMessage("You already have an account", "danger");
                    return;
                }

                $query = "INSERT INTO `userdata` (username, email, password) VALUES(?, ?, ?)";
                $queryStmt = $databaseObj->connectdb->prepare($query);
                $queryStmt->bind_param("sss", $username, $email, $hashPassword);
                if ($queryStmt->execute()) {
                    header("Location: login.php?doneSignUp=1");
                    } else {
                    Warnings::printMessage("Error in Account Creation", "danger");
                }
            }
        }
    }

    public function login(): void
    {
        if (isset($_POST['logInBtn'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $db = new Database();
            $selectQuery = "SELECT * FROM `userdata` WHERE email = ?";
            $prepareStmtObj = $db->connectdb->prepare($selectQuery);
            $prepareStmtObj->bind_param('s', $email);
            $checkQuery = $prepareStmtObj->execute();
            if (!$checkQuery) {
                Warnings::printMessage("Something went wrong", "danger");
                return;
            }
            $resultObj = $prepareStmtObj->get_result();
            if ($resultObj->num_rows == 0) {
                Warnings::printMessage("Email not found", "danger");
                return;
            }
            $rowArr = $resultObj->fetch_assoc();
            $dbHashedPassword = $rowArr['password'];
            if (!password_verify($password, $dbHashedPassword)) {
                Warnings::printMessage("Wrong password", "danger");
                return;
            }
            $_SESSION['userID'] = $rowArr['user_id'];
            $_SESSION['userName'] = $rowArr['username'];
            header('Location: index.php');
        }
    }
    
    public function isAuth()
    {
        return isset($_SESSION['userID']);
    }

    public function redirectIfAuth()
    {
        if ($this->isAuth()) header('Location: index.php');
    }

    public function redirectIfNotAuth()
    {
        if (!$this->isAuth()) header('Location: login.php');
    }

    public function logout()
    {
        if (isset($_GET['logout'])) {
            session_unset();
            session_destroy();
            header('Location: login.php');
        }
    }
}


