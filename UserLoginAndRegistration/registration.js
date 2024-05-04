// I certify that this submission is my own original work

// This file contains code for form validation; performs checks on the user input such as the username, email, password and confirm password if the meet the criteria. Communicates with the server to verify if the username and email are unique, and upon checking performs appropriate action, such as displaying error messages. Upon successful input checks the form data is submitted to the server and redirects the user to the login page


window.onload = () => {
  setupPasswordToggle("pass", "eye");
  setupPasswordToggle("confPass", "eye2");
};

function setupPasswordToggle(loginPass, loginEye) {
  const input = document.getElementById(loginPass);
  const iconEye = document.getElementById(loginEye);

  iconEye.addEventListener("click", () => {
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

// This function performs the javascript validation
function validateForm() {
  const form = document.getElementById("registrationForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // I will use the the fetch Api of javascript for a dynamic user experience
    const username = document.getElementById("username").value;
    const email = document.getElementById("email").value;
    const password = document.getElementById("pass").value;
    const confPass = document.getElementById("confPass").value;

    // In this array i will accumulate all the error messages if any and halt the application before making the api request to the server
    let errorMessage = [];

    //! ===== cheking username =====
    if (username.length < 5) {
      // The user name must be at least 5 characters long
      errorMessage.push(
        "The username field must be filled out and at least 5 characters long"
      );
    }
    if (!/^[a-zA-Z0-9_-]+$/.test(username)) {
      // No symbols allowed other than _ and - are allowed
      errorMessage.push(
        "The username must only containe a-z, A-Z, 0-9, - and _"
      );
    }

    //! ===== cheking email =====
    if (email.length === 0) {
      errorMessage.push("The email field must be filled out");
    } else if (
      !(email.indexOf(".") > 0 && email.indexOf("@") > 0) ||
      /[^a-zA-Z0-9.@_-]/.test(email)
    ) {
      errorMessage.push("The Email address is invalid");
    }

    //! ===== cheking password =====
    if (password.length < 8) {
      // if the password is less than 8 characters
      errorMessage.push(
        "The password field must be filled out and at least 8 characters long."
      );
    }
    // Validating if the password is correct
    if (!isValidPassword(password)) {
      errorMessage.push("The passwords require one of each a-z, A-Z and 0-9 ");
    }
    if (password !== confPass) {
      // if passwords dont match
      errorMessage.push(
        "The passwords you entered do not match! Please try again."
      );
    }

    doExistUserNameEmail(username, email, (errors) => {
      // adding the both arrays together, one from the current function another from the doExistUserNameEmail() function
      errorMessage = errorMessage.concat(errors);

      //! ===== display error message if applicable =====
      if (errorMessage.length > 0) {
        // Adding a number to each error message for a better user experience
        alert(
          errorMessage.map((err, index) => `${index + 1}. ${err}`).join("\n")
        );
      } else {
        // If there are no error then we send the request to a new user to be added
        fetch("processUsers.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/x-www-form-urlencoded", // This header makes sure the data is sent as a form structure.
          },
          // sending the username, email and password
          body: `flag=${encodeURIComponent(
            "addUser"
          )}&username=${encodeURIComponent(
            username
          )}&email=${encodeURIComponent(email)}&password=${encodeURIComponent(
            password
          )}`,
        })
          .then((response) => response.text())
          .then((data) => {
            alert(data);
            if (data.includes("User account created succesfully")) {
              form.reset();
              window.location.href = "./login.html";
            }
          });
      }
    });
  });
}

validateForm();

function isValidPassword(password) {
  const hasUpperCase = /[A-Z]/.test(password); // Checks if the password has uppercase characters
  const hasLowerCase = /[a-z]/.test(password); // Checks if the password has lowercase characters
  const hasNumber = /[0-9]/.test(password); // Checks if the password has numbers

  return hasUpperCase && hasLowerCase && hasNumber;
}

// This method checks if there are any existing username or email address
function doExistUserNameEmail(username, email, callback) {
  // Using fetch Api to check if the username and email exists
  fetch("processUsers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded", // This header makes sure the data is sent as a form structure.
    },
    // sending the username and email entered by the user to check
    body: `flag=${encodeURIComponent(
      "checkUsernameAndEmail"
    )}&username=${encodeURIComponent(username)}&email=${encodeURIComponent(
      email
    )}`,
  })
    .then((response) => response.text())
    .then((data) => {
      // console.log(data);
      // An error array to accumulate the errors
      let errors = [];
      if (data.includes("username")) {
        // if the server returns username as the array element that means there is an username exists
        errors.push(
          "Selected username is not available! Please choose another username!"
        );
      }
      if (data.includes("email")) {
        // if the server returns email as the array element that means there is an email exists
        errors.push(
          "There is already an account assocciated with the entered email!"
        );
      }
      // sending the error array in the custom callback, using callback since fetch api is Asynchronous
      callback(errors);
    })
    .catch((error) => {
      console.error("Error:", error);
      // If there is an error while verifying, i am sending the error message as an array element
      callback(["An error occurred while verifying your username and email."]);
    });
}
