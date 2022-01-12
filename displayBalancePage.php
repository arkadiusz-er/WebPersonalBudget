<?php
	session_start();
	
	if(!isset($_SESSION['logged'])) {
		header('Location: index.php');
		exit();
	}
	
	require_once 'connect.php';
	mysqli_report(MYSQLI_REPORT_STRICT);
		
	try {
		$connection = new mysqli($host, $db_user, $db_password, $db_name);
		if($connection->connect_errno!=0) {
			throw new Exception (mysqli_connect_errno());
		} else {
			$user_id = $_SESSION['logged_id'];
			unset($_SESSION['expenses_vis_string']);
			unset($_SESSION['bilansInfo']);
			unset($_SESSION['bilansInfo']);
			unset($_SESSION['incomes']);
			unset($_SESSION['expenses']);
			unset($_SESSION['balance']);
			
			$now = date("Y-m-d");
			$dateStart = date("Y-m-d", strtotime("first day of this month"));
			$dateEnd = date("Y-m-d", strtotime($now));
			if(isset($_POST['submit'])) {
				if ($_POST['periodOfTime'] == 'currentMonth') {
					$dateStart = date("Y-m-d", strtotime("first day of this month"));
					$dateEnd = date("Y-m-d", strtotime($now));
				} else if ($_POST['periodOfTime'] == 'previousMonth') {
					$dateStart = date("Y-m-d", strtotime("first day of -1 month"));
					$dateEnd = date("Y-m-d", strtotime("last day of -1 month"));
				} else if ($_POST['periodOfTime'] == 'currentYear') {
					$dateStart = date("Y-m-d", strtotime("first day of this year"));
					$dateEnd = date("Y-m-d", strtotime($now));
				} else if ($_POST['periodOfTime'] == 'selectedPeriod') {
					//$dateStart = $_POST['startDate'];
					//$dateEnd = $_POST['endDate'];
				}
				
				$_SESSION['bilansInfo'] = '<h2 class="h3 mt-3">Bilans za okres '.$dateStart.' - '.$dateEnd.'</h2>
                        <article class="p-1">';
				
				//Sprawdzenie, czy user ma jakieś przychody
				$incomes = $connection->query("SELECT ica.name, SUM(i.amount) FROM users u 
				INNER JOIN incomes i ON u.id=i.user_id 
				INNER JOIN incomes_category_assigned_to_users ica ON i.income_category_assigned_to_user_id = ica.id
				WHERE u.id=$user_id AND i.date_of_income >= '$dateStart' AND i.date_of_income <= '$dateEnd'
				GROUP by ica.id ORDER BY SUM(i.amount) DESC");
				
				$numbers_incomes = $incomes->num_rows;

				if($numbers_incomes>0) {
					$incomes_string = '
						<div class="d-inline-block mb-1 m-auto">
							<h3 class="h6">Przychody za '.$dateStart.' - '.$dateEnd.'</h3>
							<table class="m-auto bg-white table table-bordered border-dark">
								<tr class="bg-success">
									<th>Kategoria</th>
									<th>Suma [zł]</th>
								</tr>
					';
					
					while ($row_incomes = $incomes->fetch_assoc()) {
						$incomes_string .= '
							<tr>
								<td>'.$row_incomes['name'].'</td>
								<td>'.$row_incomes['SUM(i.amount)'].'</td>
							</tr>
						';
					}
					
					$incomes_string .= '</table>
								</div>';
								
					//Suma przychodów
					$incomes_sum = $connection->query("SELECT SUM(i.amount) FROM users u 
					INNER JOIN incomes i ON u.id=i.user_id 
					INNER JOIN incomes_category_assigned_to_users ica ON i.income_category_assigned_to_user_id = ica.id
					WHERE u.id=$user_id AND i.date_of_income >= '$dateStart' AND i.date_of_income <= '$dateEnd'");

					$incomes_sum = $incomes_sum->fetch_assoc();
					$_SESSION['sum_incomes'] = $incomes_sum['SUM(i.amount)'];
					
				} else {
					$incomes_string = '
						<div class="d-inline-block p-3 mb-1 m-auto">
							<h3 class="h6">Przychody za '.$dateStart.' - '.$dateEnd.'</h3>
							<b>Brak przychodów</b>
						</div>';
						$_SESSION['sum_incomes'] = 0;
				}
				
				$_SESSION['incomes'] = $incomes_string;
				
				//Sprawdzenie, czy user ma jakieś wydatki
				$expenses= $connection->query("SELECT eca.name, SUM(e.amount) FROM users u 
				INNER JOIN expenses e ON u.id=e.user_id 
				INNER JOIN expenses_category_assigned_to_users eca ON e.expense_category_assigned_to_user_id = eca.id
				WHERE u.id=$user_id AND e.date_of_expense >= '$dateStart' AND e.date_of_expense <= '$dateEnd'
				GROUP by eca.id ORDER BY SUM(e.amount) DESC");
				
				$numbers_expenses = $expenses->num_rows;
				
				if($numbers_expenses>0) {
					$expenses_string = '
						<div class="d-inline-block p-3 mb-1 m-auto">
							<h3 class="h6">Wydatki za '.$dateStart.' - '.$dateEnd.'</h3>
							<table class="m-auto bg-white table table-bordered border-dark">
								<tr class="bg-danger">
									<th>Kategoria</th>
									<th>Suma [zł]</th>
								</tr>
					';
					
					$_SESSION['expenses_vis_string'] = "";
					while ($row_expenses = $expenses->fetch_assoc()) {
						$expenses_string .= '
							<tr>
								<td>'.$row_expenses['name'].'</td>
								<td>'.$row_expenses['SUM(e.amount)'].'</td>
							</tr>
						';
						$_SESSION['expenses_vis_string'] .= "['".$row_expenses['name']."', ".$row_expenses['SUM(e.amount)']."],";
					}
					
					$expenses_string .= '</table>
								</div>';
								
					//Suma wydatków
					$expenses_sum = $connection->query("SELECT SUM(e.amount) FROM users u 
					INNER JOIN expenses e ON u.id=e.user_id 
					INNER JOIN expenses_category_assigned_to_users eca ON e.expense_category_assigned_to_user_id = eca.id
					WHERE u.id=$user_id AND e.date_of_expense >= '$dateStart' AND e.date_of_expense <= '$dateEnd'");

					$expenses_sum = $expenses_sum->fetch_assoc();
					$_SESSION['sum_expenses'] = $expenses_sum['SUM(e.amount)'];
					
				} else {
					$expenses_string = '
						<div class="d-inline-block p-3 mb-1 m-auto">
							<h3 class="h6">Wydatki za '.$dateStart.' - '.$dateEnd.'</h3>
							<b>Brak wydatków</b>
						</div>';
					$_SESSION['sum_expenses'] = 0;
				}
				
				$_SESSION['expenses'] = $expenses_string;
				$result = $_SESSION["sum_incomes"] - $_SESSION["sum_expenses"];
				
				$balance_string = '<div class="w-50 m-auto mb-4">
									<table class="m-auto bg-white w-100 table table-bordered border-dark">
										<tr><th>Suma przychodów</th><td>
											'.$_SESSION["sum_incomes"].'
										zł</td></tr>
										<tr><th>Suma wydatków</th><td>
											'.$_SESSION["sum_expenses"].'
										zł</td></tr>
										<tr><th>Bilans</th><td>
											'.$result.'
										zł</td></tr>
									</table>
							</div>';
					
				$_SESSION['balance'] = $balance_string;
			}	
			$connection->close();
		}
	} catch (Exception $err) {
		echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o dodanie przychodu w innym terminie!</span>';
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
    <script src="https://www.gstatic.com/charts/loader.js"></script>
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
                <li class="nav-item mx-3"><a class="nav-link " href="addIncomePage.php">Dodaj przychód</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="addExpensePage.php">Dodaj wydatek</a></li>
                <li class="nav-item mx-3"><a class="nav-link active" href="displayBalancePage.php">Przeglądaj bilans</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="settingsPage.php">Ustawienia</a></li>
                <li class="nav-item mx-3"><a class="nav-link" href="logout.php"><i class="icon-logout"></i>Wyloguj się</a></li>
            </ul>
        </div>
    </nav>
    <main>
        <div class="container">
            <div class="row">
                <section class="col-sm-10 offset-sm-1 text-center pt-2">
					<div class="col-md-4 offset-sm-1 m-auto">
						<form method="post">
							<label for="periodOfTime">Wybierz okres czasu</label>
							<select class="form-control" name="periodOfTime">
								<option value="currentMonth" <?php if(isset($_POST['submit'])) if ($_POST['periodOfTime'] == 'currentMonth') echo "selected"; ?>>Bieżący miesiąc</option>
								<option value="previousMonth" <?php if(isset($_POST['submit'])) if ($_POST['periodOfTime'] == 'previousMonth') echo "selected"; ?>>Poprzedni miesiąc</option>
								<option value="currentYear" <?php if(isset($_POST['submit'])) if ($_POST['periodOfTime'] == 'currentYear') echo "selected"; ?>>Biężący rok</option>
								<option value="selectedPeriod" <?php if(isset($_POST['submit'])) if ($_POST['periodOfTime'] == 'selectedPeriod') echo "selected"; ?>
								>Wybrany okres</option>
							</select>
							<button type="submit" class="btn btn-success" name="submit">Wybierz</button>
						</form>
						<?php
							if(isset($_POST['submit'])) {
								if ($_POST['periodOfTime'] == 'selectedPeriod') {
								echo 
								'<form method="post">
									<div class="my-md-2"><label for="dateID2">Data</label><input type="date" id="dateID2" name="startDate" class="form-control-white" /></div>
									<div class="my-md-2"><label for="dateID3">Data</label><input type="date" id="dateID3" name="endDate" class="form-control-white" /></div>
									<button type="submit" class="btn btn-success" name="submit2">Wyświetl</button>
								</form>';
								}
							}
						?>			
					</div>
                    <section>
					<?php
						if (isset($_SESSION['bilansInfo'])) {
							echo $_SESSION['bilansInfo'];
							echo $_SESSION['incomes'];
							echo $_SESSION['expenses'];
							echo $_SESSION['balance'];
						}
					?>
						<script type="text/javascript">
							google.charts.load('current', {'packages':['corechart']});
							google.charts.setOnLoadCallback(drawChart);

							function drawChart() {

								var data = google.visualization.arrayToDataTable([
								  ['Name', 'Amount'],
									<?php 
										echo $_SESSION['expenses_vis_string'];
									?>
								]);

								var options = {
								  title: 'My expenses [zł]'
								};

								var chart = new google.visualization.PieChart(document.getElementById('chart_div'));

								chart.draw(data, options);
							}

							$(window).on("throttledresize", function (event) {
								var options = {
									width: '100%',
									height: '100%'
								};

								var data = google.visualization.arrayToDataTable([]);
								drawChart(data, options);
							});
						</script>
						<?php 
							if (isset($_SESSION['expenses_vis_string'])) {
								echo '<div id="chart_wrap" class="border border-dark"><div id="chart_div"></div></div>';
							}
						?>
                    </section>
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

    <script src="throttledresize.js"></script>
    <script src="script.js"></script>
	

</body>
</html>