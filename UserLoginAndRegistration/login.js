//  Importing necessary modules
import { doExistUsernameAndPassword } from "./Helper/doExistUsernameAndPassword.js";

console.log(doExistUsernameAndPassword);

// This file contains JavaScript code for login validation. Sends the username and password to the processUsers.php for checking the validity of the user using fetch API and after verification logs in and routes the user to appropriate menu

//! ===== Toggle viewing of the password =====
window.onload = () => {
  // On load of the page we pass the id of the password field and the eye icon
  setupPasswordToggle("pass", "eye");
};
function setupPasswordToggle(loginPass, loginEye) {
  const input = document.getElementById(loginPass);
  const iconEye = document.getElementById(loginEye);

  iconEye.addEventListener("click", () => {
    // On click event we toggle the password field to text field and vice versa
    if (input.type === "password") {
      input.type = "text";
      iconEye.classList.add("ri-eye-line");
      iconEye.classList.remove("ri-eye-off-line");
    } else {
      input.type = "password";
      iconEye.classList.remove("ri-eye-line");
      iconEye.classList.add("ri-eye-off-line");
    }
  });
}

//! ------------------------------

//! ===== Login Validation =====

function validateLogin() {
  const form = document.getElementById("loginForm");
  form.addEventListener("submit", function (e) {
    e.preventDefault(); // Preventing the form from submitting
    const username = document.getElementById("username").value;
    const password = document.getElementById("pass").value;
    const reportType = document.querySelector(
      'input[name="reportType"]:checked'
    ).value;

    // Array that accumulates error messages
    let errorMessage = [];

    // cheking username field
    if (username.length === 0) {
      errorMessage.push("The username field must be filled out!");
    }

    // cheking password field
    if (password.length === 0) {
      errorMessage.push("The password field must be filled out!");
    }

    // By invoking doExistUsernameAndPassword() function we check if the user and password actually exists and matches
    doExistUsernameAndPassword(username, password, reportType, (errors) => {
      // Adding both errors arrays together, one from the current function another from the doExistUsernameAndPassword() function, which also returns an error array
      errorMessage = errorMessage.concat(errors);

      // Display error message if applicable
      if (errorMessage.length > 0) {
        alert(
          errorMessage.map((err, index) => `${index + 1}. ${err}`).join("\n") // Also numbering each error and joining with new line
        );
      } else {
        // Display appropriate menu
        // Executing this block ensures the user is verified.
        form.reset(); // Resetting the form after login
        window.location.href = "../welcomePage.php";
      }
    });
  });
}

validateLogin();
