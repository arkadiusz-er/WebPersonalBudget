<?php
	session_start();
	
	if(!isset($_SESSION['logged'])) {
		header('Location: index.php');
		exit();
	}
	
	if(isset($_POST['submit'])) {
		
		$correct_data = true;
 
		//Pobranie wartości
		$login = $_SESSION['logged_username'];
		$user_id = $_SESSION['logged_id'];
			
		$amount = $_POST['amount-income'];
		$date = $_POST['date-income'];
		
		$income_comm = $_POST['comment-income'];
			
		if(!$amount) {
			$correct_data = false;
			$_SESSION['err_inc_amount'] = "Nie wpisano kwoty!";
		}
	
		if(!$date) {
			$correct_data = false;
			$_SESSION['err_inc_date'] = "Nie wybrano daty!";
		}
		
		if(!isset($_POST['income-category'])) {
			$correct_data = false;
			$_SESSION['err_inc_cat'] = "Nie wybrano kategorii przychodu!";
		} else {
			$income_category = $_POST['income-category'];
			$_SESSION['fp_inc_cat'] = $income_category;
		}
		
		$_SESSION['fp_amount'] = $amount;
		$_SESSION['fp_date'] = $date;
		
		require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		if ($correct_data == true) {
			try {
				$connection = new mysqli($host, $db_user, $db_password, $db_name);
				if($connection->connect_errno!=0) {
					throw new Exception (mysqli_connect_errno());
				} else {
					//Sprawdzenie, czy do usera są przypisane jakieś kategorie
					$income_names = $connection->query("SELECT name FROM incomes_category_assigned_to_users WHERE user_id='$user_id'");
					$numbers_income_names = $income_names->num_rows;
					if($numbers_income_names>0) {
						$income_id = $connection->query("SELECT id FROM incomes_category_assigned_to_users WHERE user_id='$user_id' AND UPPER(name)=UPPER('$income_category')");
						$row = $income_id->fetch_assoc();
						$income_id = $row['id'];
						$connection->query("INSERT INTO incomes VALUES (NULL,'$user_id','$income_id','$amount','$date','$income_comm')");
					} else {
						echo "Brak kategorii przychodów.";
					}
					
					$connection->close();
				}
			} catch (Exception $err) {
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie przychodu w innym terminie!</span>';
			}
		}	
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
    <link href="css/fontello.css" rel="stylesheet" />
</head>
<body>
    <header>
        <h1>Budżet osobisty</h1>
    </header>
    <nav class="navbar navbar-dark bg-topnav navbar-expand-lg">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="Przełącznik nawigacji">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainmenu">
            <ul class="navbar-nav m-auto">
                <li class="nav-item mx-3"><a class="nav-link" href="mainPage.php">Strona główna</a></li>
                <li class="nav-item mx-3"><a class="nav-link active" href="addIncomePage.php">Dodaj przychód</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="addExpensePage.php">Dodaj wydatek</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="displayBalancePage.php">Przeglądaj bilans</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="settingsPage.php">Ustawienia</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="logout.php"><i class="icon-logout"></i>Wyloguj się</a></li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="container">
            <div class="row">
                <section class="col-md-6 offset-md-3 mx-md-auto text-center mt-4 formularz">
                    <h2>Dodawanie przychodu</h2>
                    <form method="post" autocomplete="off">
                        <div class="my-md-2"><label for="amount">Kwota</label><input type="number" step="0.01" id="amount" name="amount-income" class="form-control-white" value="<?php
							if(isset($_SESSION['fp_amount'])) {
								echo $_SESSION['fp_amount'];
								unset($_SESSION['fp_amount']);
							}
						?>"/></div>
						<?php 
								if(isset($_SESSION['err_inc_amount'])) {
									echo '<div class="text-danger">'.$_SESSION['err_inc_amount'].'</div>';
									unset($_SESSION['err_inc_amount']);
								}
						?>
                        <div class="my-md-2"><label for="dateID">Data</label><input type="date" id="dateID" name="date-income" class="form-control-white" value="<?php
							if(isset($_SESSION['fp_date'])) {
								echo $_SESSION['fp_date'];
								unset($_SESSION['fp_date']);
							}
						?>"/></div>
						<?php 
								if(isset($_SESSION['err_inc_date'])) {
									echo '<div class="text-danger">'.$_SESSION['err_inc_date'].'</div>';
									unset($_SESSION['err_inc_date']);
								}
						?>
                        <div class="my-md-2">
                            <fieldset>
                                <legend>Kategoria przychodu</legend>
                                <div><label><input type="radio" name="income-category" value="Interest" <?php
									if ((isset($_SESSION['fp_inc_cat'])) AND ($_SESSION['fp_inc_cat'] == 'Interest')) {
										echo "checked";
										unset($_SESSION['fp_inc_cat']);
									}
								?>/>Odsetki bankowe</label></div>
                                <div><label><input type="radio" name="income-category" value="Allegro" <?php
									if ((isset($_SESSION['fp_inc_cat'])) AND ($_SESSION['fp_inc_cat'] == 'Allegro')) {
										echo "checked";
										unset($_SESSION['fp_inc_cat']);
									}
								?>/>Sprzedaż na allegro / olx </label></div>
                                <div><label><input type="radio" name="income-category" value="Salary" <?php
									if ((isset($_SESSION['fp_inc_cat'])) AND ($_SESSION['fp_inc_cat'] == 'Salary')) {
										echo "checked";
										unset($_SESSION['fp_inc_cat']);
									}
								?>/>Wynagrodzenie</label></div>
                                <div><label><input type="radio" name="income-category" value="Another" <?php
									if ((isset($_SESSION['fp_inc_cat'])) AND ($_SESSION['fp_inc_cat'] == 'Another')) {
										echo "checked";
										unset($_SESSION['fp_inc_cat']);
									}
								?>/>Inne</label></div>
                            </fieldset>
							<?php 
								if(isset($_SESSION['err_inc_cat'])) {
									echo '<div class="text-danger">'.$_SESSION['err_inc_cat'].'</div>';
									unset($_SESSION['err_inc_cat']);
								}
							?>
                        </div>
                        <div class="my-md-2">
                            <label for="comment">Komentarz</label><input type="text" id="comment" name="comment-income" class="form-control-white" maxlength="20" />
                        </div>
                        <div class="my-md-2">
                            <button type="submit" class="btn-submit" name="submit">Dodaj</button>
                            <button type="reset" class="btn-reset">Anuluj</button>
                        </div>
                    </form>
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
    <script src="script.js"></script>

</body>
</html>