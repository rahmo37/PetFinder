window.onload = () => {
  setupPasswordToggle("pass", "eye");
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

function validateLogin() {
  const form = document.getElementById("loginForm");
  form.addEventListener("submit", function (e) {
    e.preventDefault();
    const username = document.getElementById("username").value;
    const password = document.getElementById("pass").value;
    const reportType = document.querySelector(
      'input[name="reportType"]:checked'
    ).value;

    let errorMessage = [];

    //! ===== cheking username field =====
    if (username.length === 0) {
      errorMessage.push("The username field must be filled out!");
    }

    //! ===== cheking password field =====
    if (password.length === 0) {
      errorMessage.push("The password field must be filled out!");
    }

    doExistUsernameAndPassword(username, password, reportType, (errors) => {
      // adding the both arrays together, one from the current function another from the doExistUsernameAndPassword() function
      errorMessage = errorMessage.concat(errors);
      //! ===== display error message if applicable =====
      if (errorMessage.length > 0) {
        alert(
          errorMessage.map((err, index) => `${index + 1}. ${err}`).join("\n")
        );
      } else {
        //! ===== display appropriate menu =====
        // Executing this block ensures the user is verified.
        form.reset();
        window.location.href = "../welcomePage.php";
      }
    });
  });
}

validateLogin();

function doExistUsernameAndPassword(username, password, reportType, callback) {
  //! ===== Sending the username and the password to be checked =====
  // I will use the the fetch Api of javascript for a dynamic user experience

  console.log("hi");
  fetch("processUsers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded", // This header makes sure the data is sent as a form structure.
    },
    // sending the username and password
    body: `flag=${encodeURIComponent(
      "checkUserValidity"
    )}&username=${encodeURIComponent(username)}&password=${encodeURIComponent(
      password
    )}&reportType=${encodeURIComponent(reportType)}`,
  })
    .then((response) => response.text())
    .then((data) => {
      // An error array to accumulate the errors
      let errors = [];
      if (data.includes("username")) {
        errors.push("Invalid username or password!");
      } else if (data.includes("password")) {
        errors.push("Invalid username or password!");
      }
      // sending the error array in the custom callback, using callback since fetch api is Asynchronous
      callback(errors);
    })
    .catch((error) => {
      console.error("Error:", error);
      // If there is an error while verifying, i am sending the error message as an array element
      callback([
        "An error occurred while verifying your username and passwrod.",
      ]);
    });
}
