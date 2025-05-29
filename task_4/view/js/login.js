// get elements
const container = document.getElementById('container');
const signUpBtn = document.getElementById('signUp');
const signInBtn = document.getElementById('signIn');
const signUpAltBtn = document.getElementById('signUpAlt');
const signInAltBtn = document.getElementById('signInAlt');

// toggle to Sign Up view
signUpBtn.addEventListener('click', () => {
    container.classList.add('right-panel-active');
});
signUpAltBtn.addEventListener('click', () => {
    container.classList.add('right-panel-active');
});

// toggle to Sign In view
signInBtn.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
});
signInAltBtn.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
});

// simple client-side form validation feedback
document.getElementById('signUpForm').addEventListener('submit', e => {
    if (!e.target.checkValidity()) {
        e.preventDefault();
        e.target.reportValidity();
    }
});
document.getElementById('signInForm').addEventListener('submit', e => {
    if (!e.target.checkValidity()) {
        e.preventDefault();
        e.target.reportValidity();
    }
});