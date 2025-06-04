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

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BOYZZZZ</title>
    <link rel="shortcut icon" href="Partials/Favicon/pixil-art-hand.png" type="image/x-icon">
    <style>
        :root {
            --primary-bg: #0a0a0a;
            --secondary-bg: #1a1a1a;
            --accent-bg: #2d2d2d;
            --primary-text: #ffffff;
            --secondary-text: #b0b0b0;
            --accent-color: #ff6b6b;
            --link-color: #64ffda;
            --hover-color: #ff4757;
            --border-color: #333;
            --gradient-1: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            --gradient-2: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            --shadow-color: rgba(0, 0, 0, 0.3);
            --glow-color: rgba(100, 255, 218, 0.3);
        }

        [data-theme="light"] {
            --primary-bg: #ffffff;
            --secondary-bg: #f8f9fa;
            --accent-bg: #e9ecef;
            --primary-text: #2c3e50;
            --secondary-text: #6c757d;
            --accent-color: #e74c3c;
            --link-color: #3498db;
            --hover-color: #e74c3c;
            --border-color: #dee2e6;
            --shadow-color: rgba(0, 0, 0, 0.1);
            --glow-color: rgba(52, 152, 219, 0.3);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--primary-bg);
            color: var(--primary-text);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            min-height: 100vh;
            transition: all 0.3s ease;
            position: relative;
            overflow-x: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.1) 0%, transparent 50%),
                        radial-gradient(circle at 80% 20%, rgba(255, 107, 107, 0.1) 0%, transparent 50%);
            pointer-events: none;
            z-index: -1;
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: var(--accent-bg);
            border: 2px solid var(--border-color);
            border-radius: 50%;
            width: 60px;
            height: 60px;
            cursor: pointer;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 20px var(--shadow-color);
        }

        .theme-toggle:hover {
            transform: rotate(180deg) scale(1.1);
            box-shadow: 0 6px 30px var(--glow-color);
        }

        .theme-icon {
            color: var(--primary-text);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sun-rays {
            transform-origin: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sun-circle {
            transform-origin: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .moon {
            transform-origin: center;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Dark theme (show sun) */
        [data-theme="dark"] .sun-rays {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        [data-theme="dark"] .sun-circle {
            opacity: 1;
            transform: scale(1);
        }

        [data-theme="dark"] .moon {
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }

        /* Light theme (show moon) */
        [data-theme="light"] .sun-rays {
            opacity: 0;
            transform: rotate(180deg) scale(0.5);
        }

        [data-theme="light"] .sun-circle {
            opacity: 0;
            transform: scale(0.5);
        }

        [data-theme="light"] .moon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInDown 1s ease-out;
        }

        .header h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: 1rem;
            font-weight: 800;
            letter-spacing: -2px;
        }

        .main-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            align-items: start;
            flex: 1;
            animation: fadeInUp 1s ease-out 0.3s both;
        }

        .content-section {
            background: var(--secondary-bg);
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 40px var(--shadow-color);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .content-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--gradient-2);
            border-radius: 20px 20px 0 0;
        }

        .content-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px var(--shadow-color);
        }

        .text-content h2 {
            color: var(--accent-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-weight: 600;
        }

        .text-content p {
            margin-bottom: 1.2rem;
            color: var(--secondary-text);
            font-size: 1.1rem;
        }

        .highlight-link {
            color: var(--link-color);
            text-decoration: none;
            font-weight: 600;
            position: relative;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .highlight-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 0;
            height: 2px;
            background: var(--link-color);
            transition: width 0.3s ease;
        }

        .highlight-link:hover {
            color: var(--hover-color);
            transform: translateY(-2px);
        }

        .highlight-link:hover::after {
            width: 100%;
            background: var(--hover-color);
        }

        .form-container {
            margin-top: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--primary-text);
            font-weight: 500;
        }

        .form-input {
            width: 100%;
            padding: 1rem;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            background: var(--accent-bg);
            color: var(--primary-text);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: var(--link-color);
            box-shadow: 0 0 0 3px var(--glow-color);
            transform: translateY(-2px);
        }

        .form-input::placeholder {
            color: var(--secondary-text);
        }

        .submit-btn {
            background: var(--gradient-2);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            margin-top: 1rem;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 87, 108, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .agreement-section {
            background: var(--accent-bg);
            padding: 1.5rem;
            border-radius: 12px;
            margin: 1.5rem 0;
            border-left: 4px solid var(--accent-color);
        }

        .agreement-section ul {
            margin-left: 1.5rem;
            margin-top: 1rem;
        }

        .agreement-section li {
            margin-bottom: 0.5rem;
            color: var(--secondary-text);
        }

        .checkbox-container {
            display: flex;
            align-items: flex-start;
            gap: 0.5rem;
            margin-top: 1rem;
        }

        .checkbox-container input[type="checkbox"] {
            margin-top: 0.3rem;
            transform: scale(1.2);
            accent-color: var(--accent-color);
        }

        .media-section {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .gif-container {
            position: relative;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px var(--shadow-color);
            transition: all 0.3s ease;
            max-width: 100%;
        }

        .gif-container:hover {
            transform: scale(1.05);
            box-shadow: 0 15px 50px var(--shadow-color);
        }

        .gif-container img {
            width: 100%;
            height: auto;
            display: block;
            border-radius: 20px;
        }

        .welcome-message {
            background: linear-gradient(45deg, #f39c12, #e67e22);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
            text-align: center;
            font-weight: 600;
            animation: pulse 2s infinite;
        }

        .user-message {
            background: linear-gradient(45deg, #17a2b8, #007bff);
            color: white;
            padding: 1rem;
            border-radius: 12px;
            margin: 1rem 0;
            text-align: center;
            font-weight: 600;
        }

        .redirect-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.9);
            color: white;
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            backdrop-filter: blur(10px);
        }

        .redirect-content {
            text-align: center;
            padding: 2rem;
            background: var(--secondary-bg);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        }

        .redirect-content h1 {
            font-size: 2rem;
            background: var(--gradient-1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        footer {
            text-align: center;
            padding: 2rem;
            color: var(--secondary-text);
            background: var(--secondary-bg);
            margin-top: 3rem;
            border-radius: 20px;
            border: 1px solid var(--border-color);
        }

        /* Animations */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.8;
            }
        }

        /* Responsive Design */
        @media screen and (max-width: 768px) {
            .main-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .content-section {
                padding: 1.5rem;
            }
            
            .header h1 {
                font-size: 2.5rem;
            }
            
            .theme-toggle {
                width: 50px;
                height: 50px;
                top: 15px;
                right: 15px;
            }
            
            .theme-toggle .theme-icon {
                width: 25px;
                height: 25px;
            }
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 20px 15px;
            }
            
            .content-section {
                padding: 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <button type="button" class="theme-toggle" data-theme-toggle>
            <svg class="theme-icon" width="30" height="30" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Sun rays -->
                <g class="sun-rays" opacity="1">
                    <path d="M12 1V3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M12 21V23" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M4.22 4.22L5.64 5.64" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M18.36 18.36L19.78 19.78" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M1 12H3" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M21 12H23" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M4.22 19.78L5.64 18.36" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                    <path d="M18.36 5.64L19.78 4.22" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                </g>
                <!-- Sun circle -->
                <circle class="sun-circle" cx="12" cy="12" r="5" stroke="currentColor" stroke-width="2" fill="none" opacity="1"/>
                <!-- Moon -->
                <path class="moon" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" stroke="currentColor" stroke-width="2" fill="none" opacity="0"/>
            </svg>
        </button>

        <header class="header">
            <h1>BOYZZZZ</h1>
        </header>

        <div class="main-content">
            <section class="content-section">
                <div class="text-content">
                    <h2>My Guys, why?</h2>
                    <p>Why are we dying earlier than women?</p>
                    <p>It doesn't make any sense</p>
                    <p>Anyway, wanna see something funny? Check 
                        <span class="highlight-link" id="linkToPage" onclick="delayRedirect()" data-link="<?php echo isset($link) ? htmlspecialchars($link) : '#'; ?>">this</span> out!
                    </p>

                    <div class="form-container">
                        <form action="Scripts/Input.php" method="post" id="myForm">
                            <div class="form-group">
                                <label for="username">Your Name</label>
                                <input class="form-input" placeholder="Enter your name" type="text" id="username" name="username" value="Damian">
                            </div>

                            <div class="form-group">
                                <label for="data">Got Funny Things to add yourself?</label>
                                <input class="form-input" placeholder="Share your memes URL" type="url" id="data" name="data" required>
                            </div>

                            <div class="agreement-section" id="agreementBlock" style="display: none;">
                                <div class="checkbox-container">
                                    <input type="checkbox" id="agree_terms" name="agree_terms" value="1">
                                    <label for="agree_terms">
                                        I agree to the following terms:
                                        <ul>
                                            <li>I acknowledge that my IP address will be stored in the database alongside my link.</li>
                                            <li>I acknowledge that the name I am providing will also be stored.</li>
                                            <li>If the link I provided proves to be of a spicy website, I may be banned from using this site ever again.</li>
                                        </ul>
                                    </label>
                                </div>
                            </div>

                            <button type="submit" class="submit-btn">Submit Your Link</button>
                        </form>

                        <?php
                        if (!empty($_SESSION["Username"]) && isset($_SESSION["Username"])) {
                            if (isset($_COOKIE["User"])) {
                                echo '<div class="welcome-message">Welcome back, ' . htmlspecialchars($_COOKIE["User"]) . '</div>';
                            }
                        }
                        
                        if (isset($_SESSION["Usermessage"])) {
                            echo '<div class="user-message">' . htmlspecialchars($_SESSION["Usermessage"]) . '</div>';
                        }
                        ?>
                    </div>
                </div>
            </section>

            <section class="content-section media-section">
                <div class="gif-container">
                    <img src="Partials/Favicon/HowYouDoing.gif" alt="Joey - How You Doing?">
                </div>
            </section>
        </div>

        <footer>
            <h6>This site is meant to put a smile on your face. No matter who you are.</h6>
        </footer>
    </div>

    <div class="redirect-overlay" id="textField">
        <div class="redirect-content">
            <h1>Thank you for choosing BOYZZZZ Airlines. See you next time!!</h1>
        </div>
    </div>

    <script>
        // Theme Toggle Functionality
        const themeToggle = document.querySelector('[data-theme-toggle]');
        const htmlElement = document.documentElement;

        function toggleTheme() {
            const currentTheme = htmlElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            htmlElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        }

        // Load saved theme
        const savedTheme = localStorage.getItem('theme') || 'dark';
        htmlElement.setAttribute('data-theme', savedTheme);

        themeToggle.addEventListener('click', toggleTheme);

        // Form interaction
        const dataInput = document.getElementById('data');
        const agreementBlock = document.getElementById('agreementBlock');

        dataInput.addEventListener('input', function() {
            if (this.value.trim() !== '') {
                agreementBlock.style.display = 'block';
            } else {
                agreementBlock.style.display = 'none';
            }
        });

        // Link redirect functionality
        function delayRedirect() {
            const overlay = document.getElementById('textField');
            overlay.style.display = 'flex';
            
            setTimeout(() => {
                const link = document.getElementById('linkToPage').getAttribute('data-link');
                if (link && link !== '#') {
                    window.location.href = link;
                } else {
                    overlay.style.display = 'none';
                    console.log('No link available');
                }
            }, 2000);
        }

        // Smooth scrolling and animations
        document.addEventListener('DOMContentLoaded', function() {
            // Add any additional initialization here
            console.log('BOYZZZZ website loaded successfully!');
        });
    </script>
</body>
</html>