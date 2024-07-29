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

<head>
  <link rel="shortcut icon" href="Partials/Favicon/pixil-art-hand.png" type="image/x-icon">
</head>

<body>
  <div class="main-void-container" id="BODY" style="display: all;">
    <main class="main">
      <Title>BOYZZZZ</Title>
      <button type="button" data-theme-toggle>
        <img src="Partials/Favicon/Light-Dark-Change.png" alt="Changer" class="img">
      </button>

      <h1 class="header">My Guys, why?</h1>

      <p>Why are we dying earlier than women?</p>
      <p>It doesn't make any sense</p>
      <p>Anyway, wanna see something funny? Check
        <a id="linkToPage" onclick="delayRedirect()" data-link="<?php echo $link ?>"> This</a> out
      </p>

      <form action="Scripts/Input.php" method="post" id="myForm">
  <h4><label>You are?</label>
    <input placeholder="Your Name" type="text" id="username" name="username" default="Damian">
  </h4>
  <h4><label>Got Funny Things to add yourself??</label>
    <input placeholder="Memes" type="url" id="data" name="data" required>
  </h4>

  <div id="agreementBlock" style="display: none;">
  <label for="agree_terms">
    <input type="checkbox" id="agree_terms" name="agree_terms" value="1" class="Agreed">
    I agree to the following terms:
    <ul>
      <li>I acknowledge that my IP address will be stored in the database alongside my link.</li>
      <li>I acknowledge that the name I am providing will also be stored.</li>
      <li>If the link I provided proves to be of a spicy website, I may be banned from using this site ever again.</li>
    </ul>
  </label>


  </div>

  <input type="Submit" value="Submit">

        </h4>
      </form>
      <?php
      if (!empty($_SESSION["Username"]) && isset($_SESSION["Username"])) {
        echo "<p style='color: yellow;'>Welcome back, " . $_COOKIE["User"] . "</p>";
      }
      if (isset($_SESSION["Usermessage"])) {
        echo "<span style='color: aqua;'>" . $_SESSION["Usermessage"] . "</span>";
      }
      ?>
    </main>

    <div class="void">
      <img class="gif" src="Partials/Favicon/HowYouDoing.gif" alt="Joey">
    </div>
  </div>
  <script src="Scripts/Light-Dark.js"></script>
  <div class="Redirect" id="textField" style="display: none;">
    <h1>Thank you for choosing .. Airlines. See you next time!!</h1>
  </div>
  <script src="Scripts/LinkUpdate.js"></script>
  <?php
  include("Partials/Pre-made/Footer.php");
  if (isset($_SESSION["Usermessage"])) {
    unset($_SESSION["Usermessage"]);
    unset($cookie_value);
  }
  ?>
</body>
</html>
