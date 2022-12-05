function checkPasswordMatch() {
	var password = $("#new_pass").val();
	var confirmPassword = $("#new_pass_confirm").val();
	if (password != confirmPassword) {
		$("#CheckPasswordMatch").html("Passwords does not match!");
		$("#submit-button").attr("disabled", true);
	}
	else {
		$("#CheckPasswordMatch").html("Passwords match.");
		$("#submit-button").attr("disabled", false);
	}
}
$(document).ready(function () {
	$("#new_pass_confirm").keyup(checkPasswordMatch);
});