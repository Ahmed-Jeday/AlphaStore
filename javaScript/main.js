import 'bootstrap/dist/css/bootstrap.min.css';


let isLoggedIn = false;

document.getElementById("user_icon").onclick = function () {
  if (isLoggedIn) {
    window.location.href = "profile.html";
  } else {
    window.location.href = "signUp.html";
    isLoggedIn = true;
  }
};