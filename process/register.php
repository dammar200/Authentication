<?php
class Register
{
    protected $userError = "";
    protected $passwordError = "";
    protected $captchaError = "";
    protected $strength = 0;
    protected $message = "";
    protected $color = "";
    protected $result;
    protected $verified = 0;
    protected $username;
    protected $password;
    protected $checkUsername;
    protected $checkPassword;
    protected $insertMessage;
    protected $countUser;
    protected $countPassword;

    function __construct($data)
    {
        session_start();
        $this->required($data);

        $_SESSION['username'] = $data['username'];
        $_SESSION['password'] = $data['password'];
        $_SESSION['captcha'] = $data['captcha'];
        if ($this->verified == 1) {
            $this->check_password($data['password']);
            if ($this->strength <= 3) {
                $_SESSION['passwordError'] = $this->message;
                $_SESSION['color'] = $this->color;
                header('Location: ../index.php');
            } else {
                $this->check_captcha($data['captcha']);
                if ($this->result) {
                    $conn = new mysqli("localhost", "root", "", "manish");
                    $this->username = $data['username'];
                    $this->checkUsername = $conn->query("SELECT * FROM users WHERE username = '$this->username'");
                    $this->countUser = mysqli_num_rows($this->checkUsername);
                    if ($this->countUser == 0) {
                        $this->password = md5($data['password']);
                        $this->checkPassword = $conn->query("SELECT * FROM users where password like '%$this->password%'");
                        $this->countPassword = mysqli_num_rows($this->checkPassword);
                        if ($this->countPassword == 0) {
                            $this->insertMessage = $conn->query("INSERT INTO users(username,password) VALUES('$this->username','$this->password')");
                            if ($this->insertMessage) {
                                $_SESSION['message'] = "Data is inserted successfully";
                                $_SESSION['color'] = "success";
                                header('Location: ../index.php');
                            } else {
                                $_SESSION['message'] = "Data insertion failed";
                                $_SESSION['color'] = "warning";
                                header('Location: ../index.php');
                            }
                        } else {
                            $_SESSION['passwordError'] = "Password is compromised! Please try with another password";
                            header('Location: ../index.php');
                        }
                    } else {
                        $_SESSION['userError'] = "Username already exists";
                        header('Location: ../index.php');
                    }
                } else {
                    $_SESSION['captcha_error'] = "Invalid Code";
                    header('Location: ../index.php');
                }
            }
        } else {
            if ($this->verified == 2) {

                $_SESSION['userError'] = $this->userError;
                header('Location: ../index.php');
            } else if ($this->verified == 3) {

                $_SESSION['passwordError'] = $this->passwordError;
                header('Location: ../index.php');
            }
            if ($this->verified == 4) {

                $_SESSION['captcha_error'] = $this->captchaError;
                header('Location: ../index.php');
            }
        }
    }

    protected function check_password($password)
    {
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);

        if (($uppercase && !$lowercase && !$number) || (!$uppercase && $lowercase && !$number) || (!$uppercase && !$lowercase && $number)) {
            $this->strength = 1;
            $this->message = "Weak Password. Try using Uppercase, Lowercase and Numbers";
            $this->color = "danger";
        }

        if ($lowercase && !$number && $uppercase) {
            $this->strength = 2;
            $this->message = "Medium Password. Try using Number.";
            $this->color = "warning";
        }

        if ($lowercase && $number && !$uppercase) {
            $this->strength = 2;
            $this->message = "Medium Password. Try using Uppercase.";
            $this->color = "warning";
        }

        if ($lowercase && $number && $uppercase) {
            $this->strength = 3;
            $this->message = "Good Password. But Password should be greater than 8 characters";
            $this->color = "primary";
        }

        if ($lowercase && $number && $uppercase && strlen($password) >= 8) {
            $this->strength = 4;
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
    $register = new Register($_POST);
}
