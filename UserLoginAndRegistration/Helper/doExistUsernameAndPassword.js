//! ===== Sending the username and the password to be checked =====
// This function makes a fetch Api call and checks if the username and password are valid, also sends the report type which helps in starting a session for an appropriate user
export function doExistUsernameAndPassword(
  username,
  password,
  reportType,
  callback
) {
  // I am using the the fetch Api of javascript for a dynamic user experience
  fetch("./processUsers.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded", // This header makes sure the data is sent as a form structure.
    },
    // sending the username and password and reporttype
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
        // If the response includes the text username we push a corresponding error message
        errors.push("Invalid username or password!");
      } else if (data.includes("password")) {
        errors.push("Invalid username or password!"); //  Else If the response includes the text password we push a corresponding error message
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
