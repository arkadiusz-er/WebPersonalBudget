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
		
		$amount = $_POST['amount-expense'];
		$date = $_POST['date-expense'];
		$expense_comm = $_POST['comment-expense'];
		
		if(!$amount) {
			$correct_data = false;
			$_SESSION['err_exp_amount'] = "Nie wpisano kwoty!";
		}
		
		if(!$date) {
			$correct_data = false;
			$_SESSION['err_exp_date'] = "Nie wybrano daty!";
		}
		
		if(!isset($_POST['payment-method'])) {
			$correct_data = false;
			$_SESSION['err_pay_cat'] = "Nie wybrano metody płatności!";
		} else {
			$payment_method = $_POST['payment-method'];
			$_SESSION['fp_pay_cat'] = $payment_method;
		}
		
		if(!isset($_POST['expense-category'])) {
			$correct_data = false;
			$_SESSION['err_exp_cat'] = "Nie wybrano kategorii wydatku!";
		} else {
			$expense_category = $_POST['expense-category'];
			$_SESSION['fp_exp_cat'] = $expense_category;
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
				$expenses_names = $connection->query("SELECT name FROM expenses_category_assigned_to_users WHERE user_id='$user_id'");
				$numbers_expenses_names = $expenses_names->num_rows;
					if($numbers_expenses_names>0) {
						$expense_id = $connection->query("SELECT id FROM expenses_category_assigned_to_users WHERE user_id='$user_id' AND UPPER(name)=UPPER('$expense_category')");
						$payment_method_id = $connection->query("SELECT id FROM payment_methods_assigned_to_users WHERE user_id='$user_id' AND UPPER(name)=UPPER('$payment_method')");
						$row_expense_id = $expense_id->fetch_assoc();
						$row_payment_method_id = $payment_method_id->fetch_assoc();
						$expense_id = $row_expense_id['id'];
						$payment_method_id = $row_payment_method_id['id'];
						$connection->query("INSERT INTO expenses VALUES (NULL,'$user_id','$expense_id','$payment_method_id','$amount','$date','$expense_comm')");
						echo "<script type='text/javascript'>alert('Wydatek został dodany!');</script>";
						unset($_SESSION['fp_amount']);
						unset($_SESSION['fp_date']);
						unset($_SESSION['fp_pay_cat']);
						unset($_SESSION['fp_exp_cat']);
					} else {
						echo "Brak kategorii wydatków.";
					}
				
				$connection->close();
				}
			} catch (Exception $err) {
				echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie wydatku w innym terminie!</span>';
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
                <li class="nav-item mx-3"><a class="nav-link" href="addIncomePage.php">Dodaj przychód</a></li>
                <li class="nav-item mx-3"><a class="nav-link active" href="addExpensePage.php">Dodaj wydatek</a></li>
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
                    <h2>Dodawanie wydatku</h2>
                    <form method="post" autocomplete="off">
                        <div class="my-md-2"><label for="amount">Kwota</label><input type="number" id="amount" step="0.01" name="amount-expense" class="form-control-white" value="<?php
							if(isset($_SESSION['fp_amount'])) {
								echo $_SESSION['fp_amount'];
								unset($_SESSION['fp_amount']);
							}
						?>"/></div>
						<?php 
								if(isset($_SESSION['err_exp_amount'])) {
									echo '<div class="text-danger">'.$_SESSION['err_exp_amount'].'</div>';
									unset($_SESSION['err_exp_amount']);
								}
						?>
                        <div class="my-md-2"><label for="dateIncExp">Data</label><input type="date" id="dateIncExp" name="date-expense" class="form-control-white" value="<?php
							if(isset($_SESSION['fp_date'])) {
								echo $_SESSION['fp_date'];
								unset($_SESSION['fp_date']);
							}
						?>"/></div>
						<?php 
								if(isset($_SESSION['err_exp_date'])) {
									echo '<div class="text-danger">'.$_SESSION['err_exp_date'].'</div>';
									unset($_SESSION['err_exp_date']);
								}
						?>
                        <div class="my-md-2">
                            <fieldset>
                                <legend>Sposób płatności</legend>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="cash" <?php
									if ((isset($_SESSION['fp_pay_cat'])) AND ($_SESSION['fp_pay_cat'] == 'cash')) {
										echo "checked";
										unset($_SESSION['fp_pay_cat']);
									}
								?>/>Gotówka</label>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="debit card" <?php
									if ((isset($_SESSION['fp_pay_cat'])) AND ($_SESSION['fp_pay_cat'] == 'debit card')) {
										echo "checked";
										unset($_SESSION['fp_pay_cat']);
									}
								?>/>Karta debetowa</label>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="credit card" <?php
									if ((isset($_SESSION['fp_pay_cat'])) AND ($_SESSION['fp_pay_cat'] == 'credit card')) {
										echo "checked";
										unset($_SESSION['fp_pay_cat']);
									}
								?>/>Karta kredytowa</label>
                            </fieldset>
							<?php 
								if(isset($_SESSION['err_pay_cat'])) {
									echo '<div class="text-danger">'.$_SESSION['err_pay_cat'].'</div>';
									unset($_SESSION['err_pay_cat']);
								}
							?>
                        </div>
                        <div class="my-md-2">
                            <fieldset>
                                <legend>Kategoria płatności</legend>
                                <div><label><input type="radio" name="expense-category" value="gift" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'gift')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Darowizna</label></div>
                                <div><label><input type="radio" name="expense-category" value="kids" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'kids')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Dzieci</label></div>
                                <div><label><input type="radio" name="expense-category" value="hygiene" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'hygiene')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Higiena</label></div>
                                <div><label><input type="radio" name="expense-category" value="food" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'food')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Jedzenie</label></div>
                                <div><label><input type="radio" name="expense-category" value="books" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'books')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Książki</label></div>
                                <div><label><input type="radio" name="expense-category" value="apartments" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'apartments')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Mieszkanie</label></div>
                                <div><label><input type="radio" name="expense-category" value="health" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'health')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Opieka zdrowotna</label></div>
                                <div><label><input type="radio" name="expense-category" value="recreation" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'recreation')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Rozrywka</label></div>
                                <div><label><input type="radio" name="expense-category" value="debt repayment" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'debt repayment')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Spłata długów</label></div>
                                <div><label><input type="radio" name="expense-category" value="telecommunication" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'telecommunication')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Telekomunikacja</label></div>
                                <div><label><input type="radio" name="expense-category" value="transport" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'transport')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Transport</label></div>
                                <div><label><input type="radio" name="expense-category" value="clothes" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'clothes')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Ubrania</label></div>
                                <div><label><input type="radio" name="expense-category" value="for retirement" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'for retirement')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Na emeryturę</label></div>
                                <div><label><input type="radio" name="expense-category" value="trip" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'trip')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Wycieczka</label></div>
                                <div><label><input type="radio" name="expense-category" value="another" <?php
									if ((isset($_SESSION['fp_exp_cat'])) AND ($_SESSION['fp_exp_cat'] == 'another')) {
										echo "checked";
										unset($_SESSION['fp_exp_cat']);
									}
								?>/>Inne wydatki</label></div>
                            </fieldset>
							<?php 
								if(isset($_SESSION['err_exp_cat'])) {
									echo '<div class="text-danger">'.$_SESSION['err_exp_cat'].'</div>';
									unset($_SESSION['err_exp_cat']);
								}
							?>
                        </div>
                        <div class="my-md-2">
                            <label for="comment">Komentarz</label><input type="text" id="comment" name="comment-expense" class="form-control-white" maxlength="20" />
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