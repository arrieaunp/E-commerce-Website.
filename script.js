var passwordInput = document.getElementById("password"); 
var passwordConfirm = document.getElementById("password_confirm"); 
var passwordMessageItems = document.getElementsByClassName("password-message-item"); 
var passwordMessage = document.getElementById("password-message"); 
const togglePassword = document.getElementById('togglePassword');
const togglePasswordCF = document.getElementById('togglePasswordCF');

  
passwordInput.onfocus = function () { 
    passwordMessage.style.display = "block"; 
} 
  
// After clicking outside of password input hide the message 
passwordInput.onblur = function () { 
    passwordMessage.style.display = "none"; 
} 
  
passwordInput.onkeyup = function () { 
    // check uppercase  
    let uppercaseRegex = /[A-Z]/g; 
    if (passwordInput.value.match(uppercaseRegex)) { 
        passwordMessageItems[1].classList.remove("invalid"); 
        passwordMessageItems[1].classList.add("valid"); 
    } else { 
        passwordMessageItems[1].classList.remove("valid"); 
        passwordMessageItems[1].classList.add("invalid"); 
    } 
  
    // check lowercase  
    let lowercaseRegex = /[a-z]/g; 
    if (passwordInput.value.match(lowercaseRegex)) { 
        passwordMessageItems[0].classList.remove("invalid"); 
        passwordMessageItems[0].classList.add("valid"); 
    } else { 
        passwordMessageItems[0].classList.remove("valid"); 
        passwordMessageItems[0].classList.add("invalid"); 
    } 
  
    // check the number 
    let numbersRegex = /[0-9]/g; 
    if (passwordInput.value.match(numbersRegex)) { 
        passwordMessageItems[2].classList.remove("invalid"); 
        passwordMessageItems[2].classList.add("valid"); 
    } else { 
        passwordMessageItems[2].classList.remove("valid"); 
        passwordMessageItems[2].classList.add("invalid"); 
    } 
  
    // check length of the password 
    if (passwordInput.value.length >= 8) { 
        passwordMessageItems[3].classList.remove("invalid"); 
        passwordMessageItems[3].classList.add("valid"); 
    } else { 
        passwordMessageItems[3].classList.remove("valid"); 
        passwordMessageItems[3].classList.add("invalid"); 
    } 

    // check special characters
    let specialCharRegex = /[!@#$%^&*]/g;
    if (passwordInput.value.match(specialCharRegex)) {
        passwordMessageItems[4].classList.remove("invalid");
        passwordMessageItems[4].classList.add("valid");
    } else {
        passwordMessageItems[4].classList.remove("valid");
        passwordMessageItems[4].classList.add("invalid");
    }
//Check Password match Confirm password
passwordConfirm.onkeyup = function () {
    const confirmPassword = passwordConfirm.value.trim();
    const password = passwordInput.value.trim();
    
    if (confirmPassword === password) {
        // Passwords match
        passwordMessageItems[5].classList.remove("invalid");
        passwordMessageItems[5].classList.add("valid");
    } else {
        // Passwords don't match
        passwordMessageItems[5].classList.remove("valid");
        passwordMessageItems[5].classList.add("invalid");
    }
}

togglePassword.addEventListener("click", function () {
    // toggle the type attribute
    var type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
    passwordInput.setAttribute("type", type);
    
    // toggle the icon
    this.classList.toggle("bi-eye");
});
togglePasswordCF.addEventListener("click", function () {
    // toggle the type attribute
    var type = passwordConfirm.getAttribute("type") === "password" ? "text" : "password";
    passwordConfirm.setAttribute("type", type);
    
    // toggle the icon
    this.classList.toggle("bi-eye");
});

// prevent form submit
const form = document.querySelector("form");
form.addEventListener('submit', function (e) {
    e.preventDefault();
});

}