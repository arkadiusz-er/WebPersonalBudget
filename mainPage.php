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
                <li class="nav-item mx-3"><a class="nav-link active" href="mainPage.php">Strona główna</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="addIncomePage.php">Dodaj przychód</a></li>
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
                <article class="col-md-6 pt-2 text-center">
                    <img src="img/personal-budget.jpg" alt="personal-budget" class="img-fluid" />
                </article>
                <article class="col-md-6 pt-5 text-center">
                    <h2 class="h2">Budżet osobisty</h2>
                    <p>Trzymanie kontroli nad osobistymi czy rodzinnymi finansami ułatwi śledzenie wydatków oraz oszczędności. Dzięki programowi będziesz mógł odpowiednio przeanalizować swój budżet domowy i oszczędzić na wymarzony samochód czy wakacje.</p>
                    <h3 class="h3">Dostępne opcje</h3>
                    <p>Dodaj przychód oraz dodaj wydatek - przy pomocy formularzy wypełniamy odpowiednie dane wybierając odpowiednio kwotę, czas, kategorię oraz opcjonalny komenatrz. Przegląd bilansu to podsumowanie naszych przychodów i wydatków w wybranym przez nas ujęciu czasu. W Ustawieniach możemy zmienić dane swojego konta, a także usuwać, modyfikować i dodawać opcje wydatków oraz przychodów - takie jak kategorie czy metody płatności.</p>
                </article>
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
