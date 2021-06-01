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