<?php
	session_start();
	
	if(!isset($_SESSION['logged'])) {
		header('Location: index.php');
		exit();
	}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Aplikacja Budżet Osobisty</title>
    <link href="style.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@400;900&display=swap" rel="stylesheet">
    <link href="css/fontello.css" rel="stylesheet" />
</head>
<body>
    <header>
        <h1>Budżet osobisty</h1>
    </header>
    <nav id="top-nav">
        <ul class="top-menu">
            <li><a href="mainPage.php" class="link-html">Strona główna</a></li>
            <li><a href="addIncomePage.php" class="link-html">Dodaj przychód</a></li>
            <li><a href="addExpensePage.php" class="link-html">Dodaj wydatek</a></li>
            <li><a href="displayBalancePage.php" class="link-html">Przeglądaj bilans</a></li>
            <li><a href="settingsPage.php" class="link-html">Ustawienia</a></li>
            <li><a href="logout.php" class="link-html"><i class="icon-logout"></i>Wyloguj się</a></li>
        </ul>
    </nav>
    <main>
        <section class="main-content">
            <p>Zmień ustawienia</p>
        </section>
    </main>
    <footer>
        Wszelkie prawa zastrzeżone &copy;
    </footer>
    <script src="jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

</body>
</html>