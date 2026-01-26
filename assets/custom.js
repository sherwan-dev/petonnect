document.addEventListener("DOMContentLoaded", function () {
    //CB10: Toggle password visibility on registration-form by clicking the eye icon
    const registrationPage = document.querySelector(".registration-page");
    if (registrationPage) {
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
    }
    // End of CB10
});
