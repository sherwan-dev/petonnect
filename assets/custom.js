document.addEventListener("DOMContentLoaded", function () {
    const passwordInput = document.querySelector(".password-input");
    const toggleIcon = document.getElementById("togglePasswordIcon");

    if (toggleIcon && passwordInput) {
        toggleIcon.addEventListener("click", function () {
            const isPassword = passwordInput.type === "password";
            passwordInput.type = isPassword ? "text" : "password";
            toggleIcon.classList.toggle("fa-eye-slash");
            toggleIcon.classList.toggle("fa-eye");
        });
    }
});
