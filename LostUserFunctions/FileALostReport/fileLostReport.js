let ownerId = 0;
// This function performs the javascript validation and send the data to be added in the database
function validateForm() {
  const form = document.getElementById("lostReportForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // I will use the the fetch Api of javascript for a dynamic user experience

    // Defining a form so that i can accumulate the form data
    let formData = new FormData();

    // Pet Information Variables
    let petName = document.getElementById("petname").value;
    let species = document.getElementById("species").value;
    let breed = document.getElementById("breed").value;
    let color = document.getElementById("color").value;
    let lastSeenLocation = document.getElementById("lastseenlocation").value;
    let lastSeenDate = document.getElementById("lastseendate").value;
    let file = document.getElementById("file").files[0];
    const acceptedImageType = ["tif", "gif", "jpg", "jpeg", "png"];
    let imageType = null;

    // Owner Information Variables
    let ownerName = document.getElementById("ownername").value;
    let contactNumber = document.getElementById("contactnumber").value;
    let email = document.getElementById("email").value;

    // In this array i will accumulate all the error messages if any and halt the application before making the api request to the server
    let errorMessages = [];

    //! ===== cheking petName =====
    // If petName is blank or n/a will convert it to Unknown
    if (petName.length === 0 || petName.toLowerCase() === "n/a") {
      petName = "Unknown";
      formData.append("petname", petName);
    } else {
      formData.append("petname", petName);
    }

    //! ===== cheking species =====
    // If species is blank we push an error message
    if (species.length === 0) {
      errorMessages.push("The Species field must be filled!");
    } else {
      formData.append("species", species);
    }

    //! ===== cheking breed =====
    // If breed is blank or n/a will convert it to Unknown
    if (breed.length === 0 || breed.toLowerCase() === "n/a") {
      breed = "Unknown";
      formData.append("breed", breed);
    } else {
      formData.append("breed", breed);
    }

    //! ===== cheking color =====
    // If color is blank we push an error message
    if (color.length === 0) {
      errorMessages.push("The Color field must be filled!");
    } else {
      formData.append("color", color);
    }

    //! ===== cheking Last Seen Location =====
    // If breed is blank or n/a will convert it to Unknown
    if (
      lastSeenLocation.length === 0 ||
      lastSeenLocation.toLowerCase() === "n/a"
    ) {
      lastSeenLocation = "Unknown";
      formData.append("lastSeenLocation", lastSeenLocation);
    } else {
      formData.append("lastSeenLocation", lastSeenLocation);
    }

    //! ===== cheking Last Seen Date =====

    if (lastSeenDate.length === 0 || lastSeenDate.toLowerCase() === "n/a") {
      lastSeenDate = "Unknown";
      formData.append("lastSeenDate", lastSeenDate);
    } else if (/[^0-9\-]/.test(lastSeenDate)) {
      // Checking for alphabetic or invalid characters
      errorMessages.push(
        "Invalid characters in date. Only numeric and hyphens are allowed. Format: yyyy-mm-dd"
      );
    } else {
      // Saving the date regex in the dateRegex variable
      /**
       * ^ asserting that the string must match the patter that follows
       * /d{4} tries to match exactly 4 digits, intended to represent the year
       * - matches the hyphen symbol after the year
       * \d{2}: Matches exactly two digits for motnth
       * - again matches the hyphen character after month
       * \d{2} lastly, matches exactly two digits for day
       */
      const dateRegex = /^\d{4}-\d{2}-\d{2}$/;
      if (dateRegex.test(lastSeenDate)) {
        // If there is a match
        const dateParts = lastSeenDate.split("-"); // We split the date as individual parts
        const year = parseInt(dateParts[0], 10); // extracting the year part and saving it in a year variable
        const month = parseInt(dateParts[1], 10); // extracting the month part and saving it in a month variable
        const day = parseInt(dateParts[2], 10); // extracting the day part and saving it in a day variable

        // Then we are creating a new date instance with year month and day variables, the intension is to check if the values of the day, month and year are with in range, because the user might enter a day or month that are beyond normal range. for example 2024-64-38, which is not valid. After creating the date object even if the users enters unwanted values it will be converted to a valid date by overflowing the value to next month or next year.
        const dateObj = new Date(year, month - 1, day);
        if (
          dateObj.getFullYear() === year &&
          dateObj.getMonth() + 1 === month &&
          dateObj.getDate() === day
        ) {
          // After converting and cheking, if the value mathches with the original we accept the date, and as usual accumulate the date in the formData
          formData.append("lastSeenDate", lastSeenDate);
        } else {
          errorMessages.push(
            "Invalid date. Please enter a date in the format YYYY-MM-DD."
          );
        }
      } else {
        errorMessages.push(
          "Incorrect date format. Please use the format YYYY-MM-DD."
        );
      }
    }

    //! ===== cheking image file =====
    // if the no image has been uploaded then we set its value to undefined
    if (file !== undefined) {
      imageType = file.name.split(".")[1];
    }
    if (imageType && !acceptedImageType.includes(imageType)) {
      errorMessages.push(
        "The Selected Image type " + imageType + " is not acceptable."
      );
    } else {
      formData.append("image", file);
      formData.append("imageExt", "." + imageType);
    }

    // If Name is blank we push an error message
    if (ownerName.length === 0) {
      errorMessages.push("The Name field must be filled!");
    } else if (/\d/.test(ownerName)) {
      errorMessages.push("The Name field cannot have any numeric values!");
    } else {
      formData.append("ownerName", ownerName);
    }

    if (contactNumber.length === 0) {
      errorMessages.push("Contact number filed cannot be empty");
    } else if (contactNumber.length !== 10) {
      console.log(contactNumber);
      errorMessages.push(
        "Contact number filed needs to 10 digit long, if you entered hyphens, please remove them and only enter digits"
      );
    } else if (/[^0-9-]+/.test(contactNumber)) {
      errorMessages.push(
        "Only numbers are allowed in the Contact number field"
      );
    } else {
      contactNumber = addHyphens(contactNumber);
    }

    if (email.length === 0) {
      errorMessages.push("Email field cannot be empty");
    }
    if (errorMessages.length > 0) {
      alert(
        errorMessages.map((err, index) => `${index + 1}. ${err}`).join("\n")
      );
    } else {
      uniqueOrAlreadyExistContactNumber(ownerName, contactNumber, email)
        .then(() => {
          if (ownerId === 0) {
            return uniqueEmail(email);
          }
        })
        .then(() => {
          console.log("All data verified");
          // Appending the rest of the information
          formData.append("contactNumber", contactNumber);
          formData.append("email", email);
          formData.append("ownerId", ownerId);
          formData.append("flag", "addRecord");

          // Sending the record to be added in the database
          fetch("processLostReport.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.text())
            .then((data) => {
              console.log(data);
              alert("Your lost report has been filed!");
            });
          form.reset();
          ownerId = 0;
          // Continue with form submission or further processing here
        })
        .catch((error) => {
          alert(error);
        });
    }
  });
}

validateForm();

function uniqueOrAlreadyExistContactNumber(ownerName, contactNumber, email) {
  return new Promise((resolve, reject) => {
    fetch("processLostReport.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `flag=checkContactNumberAndEmail&ownerName=${encodeURIComponent(
        ownerName
      )}&contactNumber=${encodeURIComponent(
        contactNumber
      )}&email=${encodeURIComponent(email)}`,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.includes("invalid")) {
          reject(
            "The contact number entered is associated with another name and email. Please check all owner information and try again."
          );
        } else if (Number(data)) {
          ownerId = Number(data);
          resolve();
        }
        resolve();
      })
      .catch((error) =>
        reject(
          "An error occurred while verifying your Contact Number: " + error
        )
      );
  });
}

function uniqueEmail(email) {
  return new Promise((resolve, reject) => {
    fetch("processLostReport.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `flag=checkEmail&email=${encodeURIComponent(email)}`,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.includes("invalid")) {
          reject(
            "The Email entered is associated with another owner account. Please check the email again."
          );
        } else {
          resolve();
        }
      })
      .catch((error) =>
        reject("An error occurred while verifying your Email: " + error)
      );
  });
}

function addHyphens(number) {
  number = number.trim();
  return (
    number.substring(0, 3) +
    "-" +
    number.substring(3, 6) +
    "-" +
    number.substring(6)
  );
}
