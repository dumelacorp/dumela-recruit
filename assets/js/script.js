function togglePassword() {
    var passwordInput = document.getElementById("password");
    var toggleIcon = document.getElementById("togglePasswordIcon");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("fa-eye");
        toggleIcon.classList.add("fa-eye-slash");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("fa-eye-slash");
        toggleIcon.classList.add("fa-eye");
    }
}

document.getElementById("username").addEventListener("input", function() {
    var usernameInput = document.getElementById("username");
    var emailHelp = document.getElementById("emailHelp");
    var emailPattern = /^[^@]+@dumelacorp\.com$/;

    if (!emailPattern.test(usernameInput.value)) {
        usernameInput.setCustomValidity("Please enter an email ending with @dumelacorp.com");
        emailHelp.style.color = "red";
    } else {
        usernameInput.setCustomValidity("");
        emailHelp.style.color = "";
    }
});

