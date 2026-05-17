function validateLoginForm() {
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const errorMessage = document.getElementById("error-message");

    // Clear previous error
    errorMessage.textContent = "";

    // Check empty fields
    if (email === "" || password === "") {
        errorMessage.textContent = "Please enter both email and password.";
        return false;
    }

    // Basic email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        errorMessage.textContent = "Please enter a valid email address.";
        return false;
    }

    // Password minimum length
    if (password.length < 6) {
        errorMessage.textContent = "Password must be at least 6 characters.";
        return false;
    }

    return true;
}