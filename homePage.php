<?php
	session_start();
	
	if((isset($_SESSION['logged'])) && ($_SESSION['logged'] = true)) {
		header('Location: mainpage.php');
		exit();
	} 
	
	if(isset($_SESSION['registred_user'])) {
		echo '<script type="text/javascript">alert("Konto zostało utworzone! Możesz się zalogować ☺")</script>';
		unset($_SESSION['registred_user']);
		
		//Usuwanie zmiennych pamiętających wartości wpisane do formularza
		if (isset($_SESSION['fr_login'])) unset($_SESSION['fr_login']);
		if (isset($_SESSION['fr_email'])) unset($_SESSION['fr_email']);
		if (isset($_SESSION['fr_pass1'])) unset($_SESSION['fr_pass1']);
		if (isset($_SESSION['fr_pass2'])) unset($_SESSION['fr_pass2']);
		
		//Usuwanie błędów rejestracji
		if (isset($_SESSION['err_login'])) unset($_SESSION['err_login']);
		if (isset($_SESSION['err_email'])) unset($_SESSION['err_email']);
		if (isset($_SESSION['err_pass1'])) unset($_SESSION['err_pass1']);
		if (isset($_SESSION['err_pass2'])) unset($_SESSION['err_pass2']);
	}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Aplikacja Budżet Osobisty</title>

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
                            <li id="login-option" class="home-active-option">Logowanie</li>
                            <a href="registration.php"><li id="registration-option" class="home-noactive-option">Rejestracja</li></a>
                        </ul>
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
							
							<?php
								if(isset($_SESSION['error']))	echo $_SESSION['error'];
								unset($_SESSION['error']);
							?>
                        </div> <!--
                        <div class="panel-registration h-75">
                            <form method="post" autocomplete="off">
                                <table class="text-center m-auto">
                                    <tr>
                                        <td class="w-25 py-2"><label for="login2" class="home-control-label">Login</label></td>
                                        <td class="w-75 py-2"><input type="text" class="form-control" name="login" id="login2" placeholder="Podaj login" /></td>
                                    </tr>
									<tr>
                                        <td class="w-25 py-2"><label for="email2" class="home-control-label">Email</label></td>
                                        <td class="w-75 py-2"><input type="email" class="form-control" name="email" id="email2" placeholder="Podaj email" /></td>
                                    </tr>
                                    <tr>
                                        <td class="w-25 py-2"><label for="password2" class="home-control-label">Hasło</label></td>
                                        <td class="w-75 py-2"><input type="password" class="form-control" name="password" id="password2" placeholder="Podaj hasło" /></td>
                                    </tr>
									<tr>
                                        <td class="w-25 py-2"><label for="password3" class="home-control-label"></label></td>
                                        <td class="w-75 py-2"><input type="password" class="form-control" name="password" id="password3" placeholder="Powtórz hasło" /></td>
                                    </tr>
                                </table>
                                <div class="row-panel-home">
                                    <button type="submit">Zarejestruj się</button>
                                </div>
                            </form>
                        </div> -->
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