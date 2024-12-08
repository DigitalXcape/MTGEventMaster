class Verification {
    static validatePasswordField(inputElement) {
        inputElement.addEventListener('input', () => {
            const password = inputElement.value;

            // Define regex patterns for validation
            const lengthPattern = /^.{8,20}$/;
            const numberPattern = /[0-9]/;
            const lowercasePattern = /[a-z]/;
            const uppercasePattern = /[A-Z]/;

            // Initialize an array to hold the requirements
            let requirements = [];

            // Check if the password meets all the requirements
            if (!lengthPattern.test(password)) {
                requirements.push("- Password must be between 8 and 20 characters long.");
            }
            if (!numberPattern.test(password)) {
                requirements.push("- Password must contain at least one number.");
            }
            if (!lowercasePattern.test(password)) {
                requirements.push("- Password must contain at least one lowercase letter.");
            }
            if (!uppercasePattern.test(password)) {
                requirements.push("- Password must contain at least one uppercase letter.");
            }

            // Set the custom validity message based on the requirements
            if (requirements.length === 0) {
                inputElement.setCustomValidity(""); // Clear any previous error message
            } else {
                inputElement.setCustomValidity(requirements.join("\n"));
            }

            // Trigger the browser's validation UI
            inputElement.reportValidity();
        });
    }

    static validateUsernameField(inputElement) {
        inputElement.addEventListener('input', () => {
            const username = inputElement.value;

            // Define regex pattern for validation (alphanumeric only)
            const usernamePattern = /^[a-zA-Z0-9]{1,50}$/;

            // Initialize the requirement message
            let requirement = "";

            // Check if the username meets the length and content requirement
            if (!usernamePattern.test(username)) {
                requirement = "- Username must be between 1 and 50 characters long and contain only alphanumeric characters.";
            }

            // Set the custom validity message based on the requirement
            if (requirement === "") {
                inputElement.setCustomValidity(""); // Clear any previous error message
            } else {
                inputElement.setCustomValidity(requirement);
            }

            // Trigger the browser's validation UI
            inputElement.reportValidity();
        });
    }

    static validateEmailField(inputElement) {
        inputElement.addEventListener('input', () => {
            const email = inputElement.value;

            // Define regex pattern for email validation
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            // Initialize the requirement message
            let requirement = "";

            // Check if the email meets the pattern requirement
            if (!emailPattern.test(email)) {
                requirement = "- Please enter a valid email address.";
            }

            // Set the custom validity message based on the requirement
            if (requirement === "") {
                inputElement.setCustomValidity(""); // Clear any previous error message
            } else {
                inputElement.setCustomValidity(requirement);
            }

            // Trigger the browser's validation UI
            inputElement.reportValidity();
        });
    }
}

// Auto-add Event Listeners
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');
    const usernameInput = document.getElementById('username');
    const emailInput = document.getElementById('email');

    if (passwordInput) {
        Verification.validatePasswordField(passwordInput);
    }

    if (usernameInput) {
        Verification.validateUsernameField(usernameInput);
    }

    if (emailInput) {
        Verification.validateEmailField(emailInput);
    }
});