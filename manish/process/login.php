<?php
class Login
{
    protected $userError = "";
    protected $passwordError = "";
    protected $captchaError = "";
    protected $message = "";
    protected $result;
    protected $verified = 0;
    protected $username;
    protected $password;
    protected $checkUsername;
    protected $countUser;

    function __construct($data)
    {
        session_start();
        $this->required($data);
        if ($this->verified == 1) {

            $this->check_captcha($data['captcha']);
            if ($this->result) {
                $conn = new mysqli("localhost", "root", "", "manish");
                $this->username = $data['username'];
                $this->password = md5($data['password']);
                $this->checkUsername = $conn->query("SELECT * FROM users WHERE username = '$this->username' AND password = '$this->password'");
                $this->countUser = mysqli_num_rows($this->checkUsername);
                if ($this->countUser == 1) {
                    $_SESSION['user'] = true;
                    $_SESSION['usern'] = $this->username;
                    header('Location: ../home.php');
                } else {
                    $_SESSION['message'] = "Credentials do not match";
                    header('Location: ../login.php');
                }
            } else {
                $_SESSION['captcha_error'] = "Invalid Code";
                header('Location: ../login.php');
            }
        } else {
            if ($this->verified == 2) {

                $_SESSION['userError'] = $this->userError;
                header('Location: ../login.php');
            } else if ($this->verified == 3) {

                $_SESSION['passwordError'] = $this->passwordError;
                header('Location: ../login.php');
            }
            if ($this->verified == 4) {

                $_SESSION['captcha_error'] = $this->captchaError;
                header('Location: ../login.php');
            }
        }
    }

    protected function check_captcha($captcha)
    {
        if ($captcha == $_SESSION['code']) {
            $this->result = 1;
        } else {
            $this->result = 0;
        }
    }

    protected function required($data)
    {
        if ($data['username'] == "") {
            $this->userError = "Username is required";
            $this->verified = 2;
        } else if ($data['password'] == "") {
            $this->passwordError = "Password is required";
            $this->verified = 3;
        } else if ($data['captcha'] == "") {
            $this->captchaError = "Captcha is required";
            $this->verified = 4;
        } else {
            $this->verified = 1;
        }
    }
};

if (isset($_POST['submit'])) {
    $login = new Login($_POST);
}
