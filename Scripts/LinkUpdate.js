function delayRedirect() {
    
    const text = document.getElementById("textField");
    text.style.display = "block";
    text.style.display = "center";

    const Foot = document.getElementById("footer");
    Foot.style.display = "none";


    const Bod = document.getElementById("BODY");
    Bod.style.display = "none";

    const clickedLink = document.getElementById("linkToPage");
    

    // Delay for 5 seconds before redirecting
    setTimeout(() => {
        const link = clickedLink.dataset.link;
        if (link) {
            window.location.href = link; // Redirect only if the data-link attribute is defined
        } else {
            console.error("data-link attribute is missing.");
        }
    }, 5000);
}

function handleFormSubmission(e) {
    if (!document.getElementById('agree_terms').checked) {
      e.preventDefault(); // Prevent form submission
      document.getElementById('agreementBlock').style.display = 'block'; // Show the agreement block
    } else {
      // Agreement checkbox is checked, proceed with form submission
      document.getElementById('agreementBlock').style.display = 'none'; // Hide the agreement block
    }
  }

  // Attach the function to the form's submit event
  document.getElementById('myForm').addEventListener('submit', handleFormSubmission);
