<?php
	session_start();
	if (isset($_POST['email'])) {
		//udana walidacja? Póki co zakładamy, że tak
		$correct_data = true;
		
		//Sprawdzenie poprawności loginu
		$login = $_POST['login'];
		
		//Najpierw sprawdzenie długości
		if((strlen($login)<3) || (strlen($login) > 20)) {
			$correct_data = false;
			$_SESSION['err_login'] = "Login musi składać się od 3 do 20 znaków!";
		}
		
		//Sprawdzenie czy znaki alfanumeryczne
		if(ctype_alnum($login)==false) {
			$correct_data = false;
			$_SESSION['err_login'] = "Nick może składać się tylko z liter i cyfr!";
		}
		
		//Sprawdzenie poprawności adresu email
		$email = $_POST['email'];
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
		if((filter_var($emailB, FILTER_SANITIZE_EMAIL)==false) || ($email!=$emailB)) {
			$correct_data = false;
			$_SESSION['err_email'] = "Podaj poprawny adres email!";
		}
		
		//Sprawdzenie poprawności hasła
		$pass1 = $_POST['password'];
		$pass2 = $_POST['password2'];
		
		if((strlen($pass1)<8) || (strlen($pass1)>20)) {
			$correct_data = false;
			$_SESSION['err_pass'] = "Hasło musi składać się od 8 do 20 znaków!";
		}
		
		if($pass1!=$pass2) {
			$correct_data = false;
			$_SESSION['err_pass'] = "Podane hasła nie są identyczne!";
		}
		
		$pass_hash = password_hash($pass1, PASSWORD_DEFAULT);
		
		//Weryfikacja recaptcha
		$secret_key = "6LcXStgdAAAAANa2ijO6-ph1CaZ1oJ6169tlyX4z";
		$check_recaptcha = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$secret_key.'&response='.$_POST['g-recaptcha-response']);
		$reply_recaptcha = json_decode($check_recaptcha);
		
		if($reply_recaptcha->success==false) {
			$correct_data = false;
			$_SESSION['err_bot'] = "Potwierdź, że nie jesteś botem!";
		}
		
		//Zapamiętywanie danych
		$_SESSION['fr_login'] = $login;
		$_SESSION['fr_email'] = $email;
		$_SESSION['fr_pass1'] = $pass1;
		$_SESSION['fr_pass2'] = $pass2;
		
		//Łączenie z bazą
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		try {
			$connection = new mysqli($host, $db_user, $db_password, $db_name);
			if($connection->connect_errno!=0) {
				throw new Exception (mysqli_connect_errno());
			} else {
				//Czy podany login już istnieje?
				$result = $connection->query("SELECT id FROM users WHERE LOWER(username) = LOWER('$login')");
				if (!$result) throw new Exception ($connection->error);
				$num_same_logins = $result->num_rows;
				if($num_same_logins>0) {
					$correct_data = false;
					$_SESSION['err_login'] = "Istnieje już taki login! Wybierz inny.";
				}
				
				//Czy podany email już istnieje?
				$result = $connection->query("SELECT id FROM users WHERE LOWER(email) = LOWER('$email')");
				if (!$result) throw new Exception ($connection->error);
				$num_same_mails = $result->num_rows;
				if($num_same_mails>0) {
					$correct_data = false;
					$_SESSION['err_email'] = "Istnieje już konto przypisane do tego adresu e-mail!";
				}
				
				//Jeżeli wszystko OK
				if($correct_data == true) {
					
					if($connection->query("INSERT INTO users VALUES (NULL, '$login', '$pass_hash', '$email')")) {
						$connection->query("INSERT INTO expenses_category_assigned_to_users (user_id, name)
							SELECT u.id, ecd.name FROM users u CROSS JOIN expenses_category_default ecd WHERE u.username='$login'");
						$connection->query("INSERT INTO incomes_category_assigned_to_users (user_id, name)
							SELECT u.id, icd.name FROM users u CROSS JOIN incomes_category_default icd WHERE u.username='$login'");
						$connection->query("INSERT INTO payment_methods_assigned_to_users (user_id, name)
							SELECT u.id, pmd.name FROM users u CROSS JOIN payment_methods_default pmd WHERE u.username='$login'");
						$_SESSION['registred_user'] = true;
						$_SESSION['logged'] = true;
						header('Location: mainpage.php');
					} else {
						throw new Exception($connection->error);
					}
					
				}
				
				$connection->close();
			}
		} catch (Exception $err) {
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym terminie!</span>';
		}
		
	}
	
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Aplikacja Budżet Osobisty</title>
	<script src='https://www.google.com/recaptcha/api.js'></script>

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;900&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Budżet osobisty</h1>
    </header>
    <main>
        <div class="container">
            <div class="row">
                <section class="col-md-6 py-4 text-center">
                    <h2 class="h2 pt-5">Aplikacja Budżet Osobisty pozwoli Ci kontrolować wydatki, abyś mógł w lepszy sposób zarządzać swoimi finansami.</h2>
                </section>
                <section class="col-md-6 py-5 text-center">
                    <div class="login-registration m-auto">
                        <ul class="panel-login-registration">
                            <a href="homepage.php"><li id="login-option" class="home-noactive-option">Logowanie</li></a>
                            <li id="registration-option" class="home-active-option">Rejestracja</li>
                        </ul> <!--
                        <div class="panel-login h-75">
                            <form method="post" action="login.php" autocomplete="off">
                                <table class="text-center m-auto">
                                    <tr>
                                        <td class="w-25 py-2"><label for="login1" class="home-control-label">Login</label></td>
                                        <td class="w-75 py-2"><input type="text" class="form-control" name="login" id="login1" placeholder="Podaj login" /></td>
                                    </tr>
                                    <tr>
                                        <td class="w-25 py-2"><label for="password1" class="home-control-label">Hasło</label></td>
                                        <td class="w-75 py-2"><input type="password" class="form-control" name="password" id="password1" placeholder="Podaj hasło" /></td>
                                    </tr>
                                </table>
                                <div class="row-panel-home">
                                    <button type="submit">Zaloguj się</button>
                                </div>
                            </form>
							
                        </div> -->
                        <div class="panel-registration h-75">
                            <form method="post" autocomplete="off">
                                <table class="text-center m-auto">
                                    <tr>
                                        <td class="w-25 py-2"><label for="login2" class="home-control-label">Login</label></td>
                                        <td class="w-75 py-2"><input type="text" class="form-control" value="<?php
											if(isset($_SESSION['fr_login'])) {
												echo $_SESSION['fr_login'];
												unset($_SESSION['fr_login']);
											}
										?>" name="login" id="login2" placeholder="Podaj login" /></td>
                                    </tr>
									<?php
											if(isset($_SESSION['err_login'])) {
												echo '<tr>'.'<td class="text-danger h6" colspan="2">'.$_SESSION['err_login'].'</td>'.'</tr>';
												unset($_SESSION['err_login']);
											}
									?>
									<tr>
                                        <td class="w-25 py-2"><label for="email2" class="home-control-label">Email</label></td>
                                        <td class="w-75 py-2"><input type="email" class="form-control" value="<?php
											if(isset($_SESSION['fr_email'])) {
												echo $_SESSION['fr_email'];
												unset($_SESSION['fr_email']);
											}
										?>"  name="email" id="email2" placeholder="Podaj email" /></td>
                                    </tr>
									<?php
											if(isset($_SESSION['err_email'])) {
												echo '<tr>'.'<td class="text-danger h6" colspan="2">'.$_SESSION['err_email'].'</td>'.'</tr>';
												unset($_SESSION['err_email']);
											}
									?>
                                    <tr>
                                        <td class="w-25 py-2"><label for="passwordA" class="home-control-label">Hasło</label></td>
                                        <td class="w-75 py-2"><input type="password" class="form-control" value="<?php
											if(isset($_SESSION['fr_pass1'])) {
												echo $_SESSION['fr_pass1'];
												unset($_SESSION['fr_pass1']);
											}
										?>"  name="password" id="passwordA" placeholder="Podaj hasło" /></td>
                                    </tr>
									<?php
											if(isset($_SESSION['err_pass'])) {
												echo '<tr>'.'<td class="text-danger h6" colspan="2">'.$_SESSION['err_pass'].'</td>'.'</tr>';
												unset($_SESSION['err_pass']);
											}
									?>
									<tr>
                                        <td class="w-25 py-2"><label for="passwordB" class="home-control-label"></label></td>
                                        <td class="w-75 py-2"><input type="password" class="form-control" value="<?php
											if(isset($_SESSION['fr_pass2'])) {
												echo $_SESSION['fr_pass2'];
												unset($_SESSION['fr_pass2']);
											}
										?>"  name="password2" id="passwordB" placeholder="Powtórz hasło" /></td>
                                    </tr>

									
                                </table>
								<div class="g-recaptcha" data-sitekey="6LcXStgdAAAAANhSvZrTeqrk91YyuDSMMrXRXlNj"></div>
									<?php
											if(isset($_SESSION['err_bot'])) {
												echo '<div class="text-danger h6" colspan="2">'.$_SESSION['err_bot'].'</div>';
												unset($_SESSION['err_bot']);
											}
									?>
                                <div class="row-panel-home">
                                    <button type="submit">Zarejestruj się</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </main>
    <footer>
        Wszelkie prawa zastrzeżone &copy;
    </footer>

    <script src="jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="script.js"></script>

</body>
</html>