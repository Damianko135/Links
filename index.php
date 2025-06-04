<?php
session_start();

include("Partials/Pre-made/header.php");
include("Scripts/Connection.php");

if (isset($_SESSION["Usermessage"]) && !empty($_SESSION["Usermessage"])) {
  setcookie('User', $_SESSION["Username"], time() + (86400 * 7));
}

// Establish a database connection
connectToDatabase();

// Fetch all available links
$linksData = executeQuery("SELECT * FROM links");

// Get a random number based on array length
$randomNumber = rand(0, count($linksData) - 1);

// Get a link from the array
$link = $linksData[$randomNumber]["Link"];
?>

<body>
  <div class="main-void-container" id="BODY">
    <main class="main">
      <div class="header">
        <h1>My Guys, why?</h1>
        <button type="button" data-theme-toggle aria-label="Toggle theme">
          <img src="Partials/Favicon/Light-Dark-Change.png" alt="Theme Toggle" class="img">
        </button>
      </div>

      <div class="content-section">
        <h2>The Big Question</h2>
        <p>Why are we dying earlier than women?</p>
        <p>It doesn't make any sense... 🤔</p>
        <p>Anyway, wanna see something funny? Check
          <a id="linkToPage" onclick="delayRedirect()" data-link="<?php echo htmlspecialchars($link); ?>">This</a> out!
        </p>
      </div>

      <div class="form-section">
        <h3>Got Funny Things to Add Yourself?</h3>
        <form action="Scripts/Input.php" method="post" id="myForm">
          <div class="form-group">
            <label for="username">Your Name:</label>
            <input 
              placeholder="Enter your name" 
              type="text" 
              id="username" 
              name="username" 
              class="form-input"
              value="<?php echo isset($_COOKIE['User']) ? htmlspecialchars($_COOKIE['User']) : 'Damian'; ?>"
              required
            >
          </div>

          <div class="form-group">
            <label for="data">Share Your Memes:</label>
            <input 
              placeholder="Paste your funny link here" 
              type="url" 
              id="data" 
              name="data" 
              class="form-input"
              required
            >
          </div>

          <div id="agreementBlock" style="display: none;">
            <label for="agree_terms">
              <input type="checkbox" id="agree_terms" name="agree_terms" value="1" class="Agreed">
              <strong>I agree to the following terms:</strong>
              <ul>
                <li>I acknowledge that my IP address will be stored in the database alongside my link.</li>
                <li>I acknowledge that the name I am providing will also be stored.</li>
                <li>If the link I provided proves to be inappropriate, I may be banned from using this site.</li>
                <li>I understand this is a fun, family-friendly platform. 🎉</li>
              </ul>
            </label>
          </div>

          <input type="submit" value="Submit Your Funny Link" class="submit-btn">
        </form>

        <?php
        // Display welcome message for returning users
        if (!empty($_SESSION["Username"]) && isset($_SESSION["Username"])) {
          if (isset($_COOKIE["User"])) {
            echo "<p style='color: yellow;'>Welcome back, " . htmlspecialchars($_COOKIE["User"]) . "! 🎉</p>";
          }
        }
        
        // Display session messages
        if (isset($_SESSION["Usermessage"])) {
          echo "<span style='color: aqua;'>" . htmlspecialchars($_SESSION["Usermessage"]) . "</span>";
        }
        ?>
      </div>
    </main>

    <div class="void">
      <div class="gif-container">
        <img class="gif" src="Partials/Favicon/HowYouDoing.gif" alt="Joey from Friends asking 'How you doin?'">
        <p style="text-align: center; margin-top: 1rem; color: var(--secondary-text); font-style: italic;">
          "How you doin'?" - Joey 😎
        </p>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="Scripts/Light-Dark.js"></script>
  <script src="Scripts/LinkUpdate.js"></script>
  
  <!-- Improved form interaction script -->
  <script>
    // Show agreement block when user starts typing in the URL field
    document.getElementById('data').addEventListener('input', function() {
      const agreementBlock = document.getElementById('agreementBlock');
      if (this.value.length > 0) {
        agreementBlock.style.display = 'block';
        agreementBlock.style.animation = 'fadeIn 0.3s ease-in';
      } else {
        agreementBlock.style.display = 'none';
      }
    });

    // Form validation
    document.getElementById('myForm').addEventListener('submit', function(e) {
      const urlInput = document.getElementById('data');
      const agreeCheckbox = document.getElementById('agree_terms');
      
      if (urlInput.value.length > 0 && !agreeCheckbox.checked) {
        e.preventDefault();
        alert('Please agree to the terms before submitting your link! 😊');
        return false;
      }
    });

    // Add fade-in animation
    const style = document.createElement('style');
    style.textContent = `
      @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
      }
    `;
    document.head.appendChild(style);
  </script>

  <div class="Redirect" id="textField" style="display: none;">
    <h1>Thank you for choosing BOYZZZZ Airlines! ✈️<br>See you next time!! 🎉</h1>
  </div>

  <?php
  // Clean up session messages
  if (isset($_SESSION["Usermessage"])) {
    unset($_SESSION["Usermessage"]);
    if (isset($cookie_value)) {
      unset($cookie_value);
    }
  }
  
  include("Partials/Pre-made/Footer.php");
  ?>
</body>
</html>