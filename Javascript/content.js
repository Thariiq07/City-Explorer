const signUpButton = document.getElementById("signUp");
const signInButton = document.getElementById("signIn");
const container = document.getElementById("container");

signUpButton.addEventListener('click',()=>{
    container.classList.add("right-panel-active");
})

signInButton.addEventListener('click',()=>{
    container.classList.remove("right-panel-active");
})    


async function validateSignUpForm(event) {
    const name = document.querySelector('.sign-up-container input[type="text"]').value;
    const email = document.querySelector('.sign-up-container input[type="email"]').value;
    const password = document.querySelector('.sign-up-container input[type="password"]').value;

    const errorDiv = document.querySelector('.sign-up-container .error');
    errorDiv.innerHTML = '';

    if (name.trim() === "") {
        errorDiv.innerHTML = 'Name is required';
        event.preventDefault(); // Prevent form submission
        return;
    }

    if (email.trim() === "") {
        errorDiv.innerHTML = 'Email is required';
        event.preventDefault(); // Prevent form submission
        return;
    }

    if (password.trim() === "") {
        errorDiv.innerHTML = 'Password is required';
        event.preventDefault(); // Prevent form submission
        return;
    }
}

// Function to validate the sign-in form
function validateSignInForm(event) {
    const email = document.querySelector('.sign-in-container input[type="email"]').value;
    const password = document.querySelector('.sign-in-container input[type="password"]').value;

    const errorDiv = document.querySelector('.sign-in-container .error');
    errorDiv.innerHTML = '';

    if (email.trim() === "") {
        errorDiv.innerHTML = 'Email is required';
        event.preventDefault(); // Prevent form submission
        return;
    }

    if (password.trim() === "") {
        errorDiv.innerHTML = 'Password is required';
        event.preventDefault(); // Prevent form submission
        return;
    }
}

// Add event listeners to the forms
document.addEventListener("DOMContentLoaded", function() {
    const signUpForm = document.querySelector('.sign-up-container form');
    signUpForm.addEventListener("submit", validateSignUpForm);

    const signInForm = document.querySelector('.sign-in-container form');
    signInForm.addEventListener("submit", validateSignInForm);
});