const container = document.querySelector('.container');
const registerBtn = document.querySelector('.register-btn');
const loginBtn = document.querySelector('.login-btn');

registerBtn.addEventListener('click', () => {
    container.classList.add('active');
    document.querySelectorAll('.input_error').forEach(error => error.style.display = 'none');
})

loginBtn.addEventListener('click', () => {
    container.classList.remove('active');
    document.querySelectorAll('.input_error').forEach(error => error.style.display = 'none');
})



