let founderId = 0;
// This function performs the javascript validation and send the data to be added in the database
function validateForm() {
  const form = document.getElementById("foundReportForm");

  form.addEventListener("submit", function (e) {
    e.preventDefault(); // I will use the the fetch Api of javascript for a dynamic user experience

    // Defining a form so that i can accumulate the form data
    let formData = new FormData();

    // Pet Information Variables
    let species = document.getElementById("species").value;
    let breed = document.getElementById("breed").value;
    let color = document.getElementById("color").value;
    let foundLocation = document.getElementById("foundLocation").value;
    let foundDate = document.getElementById("foundDate").value;
    let file = document.getElementById("file").files[0];
    const acceptedImageType = ["tif", "gif", "jpg", "jpeg", "png", "JPG"];
    let imageType = null;

    // Finder Information Variables
    let founderName = document.getElementById("founderName").value;
    let contactNumber = document.getElementById("contactnumber").value;
    let email = document.getElementById("email").value;

    // console.log(
    //   species,
    //   breed,
    //   color,
    //   foundLocation,
    //   foundDate,
    //   file,
    //   founderName,
    //   contactNumber,
    //   email
    // );

    // In this array i will accumulate all the error messages if any and halt the application before making the api request to the server
    let errorMessages = [];

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

    //! ===== cheking Found Location =====
    // If Found Location is blank or n/a will convert it to Unknown
    if (foundLocation.length === 0 || foundLocation.toLowerCase() === "n/a") {
      foundLocation = "Unknown";
      formData.append("foundLocation", foundLocation);
    } else {
      formData.append("foundLocation", foundLocation);
    }

    //     //! ===== cheking Last Seen Date =====
    if (foundDate.length === 0 || foundDate.toLowerCase() === "n/a") {
      foundDate = "Unknown";
      formData.append("foundDate", foundDate);
    } else if (/[^0-9\-]/.test(foundDate)) {
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
      if (dateRegex.test(foundDate)) {
        // If there is a match
        const dateParts = foundDate.split("-"); // We split the date as individual parts
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
          formData.append("foundDate", foundDate);
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

    //     //! ===== cheking image file =====
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
    if (founderName.length === 0) {
      errorMessages.push("The Name field must be filled!");
    } else if (/\d/.test(founderName)) {
      errorMessages.push("The Name field cannot have any numeric values!");
    } else {
      formData.append("founderName", founderName);
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
      uniqueOrAlreadyExistContactNumber(founderName, contactNumber, email)
        .then(() => {
          if (founderId === 0) {
            return uniqueEmail(email);
          }
        })
        .then(() => {
          console.log("All data verified");
          // Appending the rest of the information
          formData.append("contactNumber", contactNumber);
          formData.append("email", email);
          formData.append("founderId", founderId);
          formData.append("flag", "addRecord");

          // Sending the record to be added in the database
          fetch("processFoundReport.php", {
            method: "POST",
            body: formData,
          })
            .then((response) => response.text())
            .then((data) => {
              console.log(data);
              alert("Your found report has been filed!");
            });
          form.reset();
          founderId = 0;
        })
        .catch((error) => {
          alert(error);
        });
    }
  });
}

validateForm();

function uniqueOrAlreadyExistContactNumber(founderName, contactNumber, email) {
  return new Promise((resolve, reject) => {
    fetch("processFoundReport.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `flag=checkContactNumberAndEmail&founderName=${encodeURIComponent(
        founderName
      )}&contactNumber=${encodeURIComponent(
        contactNumber
      )}&email=${encodeURIComponent(email)}`,
    })
      .then((response) => response.text())
      .then((data) => {
        if (data.includes("invalid")) {
          reject(
            "The contact number entered is associated with another name and email. Please check all founder information and try again."
          );
        } else if (Number(data)) {
          founderId = Number(data);
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
    fetch("processFoundReport.php", {
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
            "The Email entered is associated with another founder account. Please check the email again."
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
