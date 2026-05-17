function validateRegisterForm() {
    const fullName = document.getElementById("full_name").value.trim();
    const email = document.getElementById("email").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const address = document.getElementById("address").value.trim();
    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirm_password").value;
    const errorMessage = document.getElementById("error-message");

    errorMessage.textContent = "";

    if (!fullName || !email || !phone || !address || !password || !confirmPassword) {
        errorMessage.textContent = "All fields are required.";
        return false;
    }

    if (!email.includes("@") || !email.includes(".")) {
        errorMessage.textContent = "Invalid email address.";
        return false;
    }

    if (phone.length < 10) {
        errorMessage.textContent = "Phone number must be at least 10 digits.";
        return false;
    }

    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters.";
        return false;
    }

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords do not match.";
        return false;
    }

    return true;
}   