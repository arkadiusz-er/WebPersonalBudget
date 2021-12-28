<?php
	session_start();
	
	if(!isset($_SESSION['logged'])) {
		header('Location: index.php');
		exit();
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
                    <form>
                        <div class="my-md-2"><label for="amount">Kwota</label><input type="number" id="amount" name="amount-expense" class="form-control-white" /></div>
                        <div class="my-md-2"><label for="dateID">Data</label><input type="date" id="dateID" name="date-expense" class="form-control-white" /></div>
                        <div class="my-md-2">
                            <fieldset>
                                <legend>Sposób płatności</legend>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="gotowka" />Gotówka</label>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="karta_debetowa" />Karta debetowa</label>
                                <label class="d-md-block"><input type="radio" name="payment-method" value="karta_kredytowa" />Karta kredytowa</label>
                            </fieldset>
                        </div>
                        <div class="my-md-2">
                            <fieldset>
                                <legend>Kategoria płatności</legend>
                                <div><label><input type="radio" name="payment-category" value="darowizna" />Darowizna</label></div>
                                <div><label><input type="radio" name="payment-category" value="dzieci" />Dzieci</label></div>
                                <div><label><input type="radio" name="payment-category" value="higiena" />Higiena</label></div>
                                <div><label><input type="radio" name="payment-category" value="jedzenie" />Jedzenie</label></div>
                                <div><label><input type="radio" name="payment-category" value="ksiazki" />Książki</label></div>
                                <div><label><input type="radio" name="payment-category" value="mieszkanie" />Mieszkanie</label></div>
                                <div><label><input type="radio" name="payment-category" value="opieka_zdrowotna" />Opieka zdrowotna</label></div>
                                <div><label><input type="radio" name="payment-category" value="rozrywka" />Rozrywka</label></div>
                                <div><label><input type="radio" name="payment-category" value="splata_dlugow" />Spłata długów</label></div>
                                <div><label><input type="radio" name="payment-category" value="szkolenie" />Szkolenie</label></div>
                                <div><label><input type="radio" name="payment-category" value="telekomunikacja" />Telekomunikacja</label></div>
                                <div><label><input type="radio" name="payment-category" value="transport" />Transport</label></div>
                                <div><label><input type="radio" name="payment-category" value="ubrania" />Ubrania</label></div>
                                <div><label><input type="radio" name="payment-category" value="wycieczka" />Wycieczka</label></div>
                                <div><label><input type="radio" name="payment-category" value="inne_wydatki" />Inne wydatki</label></div>
                            </fieldset>
                        </div>
                        <div class="my-md-2">
                            <label for="comment">Komentarz</label><input type="text" id="comment" name="comment-expense" class="form-control-white" maxlength="20" />
                        </div>
                        <div class="my-md-2">
                            <button type="submit" class="btn-submit">Dodaj</button>
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