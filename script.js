function displayRegistrationPanel() {
    $('.panel-login').css("display", "none");
    $('.panel-registration').css("display", "block");
    $('#login-option').removeClass("home-active-option");
    $('#login-option').addClass("home-noactive-option");
    $('#registration-option').removeClass("home-noactive-option");
    $('#registration-option').addClass("home-active-option");
}

function displayLoginPanel() {
    $('.panel-login').css("display", "block");
    $('.panel-registration').css("display", "none");
    $('#login-option').removeClass("home-noactive-option");
    $('#login-option').addClass("home-active-option");
    $('#registration-option').removeClass("home-active-option");
    $('#registration-option').addClass("home-noactive-option");
}

$('#login-option').on("click", displayLoginPanel);
$('#registration-option').on("click", displayRegistrationPanel);

var today = new Date();
var day = today.getDate();
var month = today.getMonth() + 1;
var year = today.getFullYear();
if (month < 10) month = "0" + month;
if (day < 10) day = "0" + day;
var maxDate = year + '-' + month + '-' + day;
$('#dateID').attr('max', maxDate);
$('#dateID').val(maxDate);

var heightDivTableResults = parseFloat($('.div-table-results:last-child').css('height'));
$('.div-table-results:first-child').css('height', heightDivTableResults);
$('.article-results').css('min-height', heightDivTableResults);
