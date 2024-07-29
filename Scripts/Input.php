<?php
include('Connection.php');    
global $PDO;



$links = isset($_REQUEST['data']) ? trim($_REQUEST['data']) : '';
$Username = isset($_REQUEST['username']) ? trim($_REQUEST['username']) : '';

if (isset($_POST['agree_terms']) && $_POST['agree_terms'] == 1) {
    // User has agreed to the terms
    // Continue with your link validation and database operations
    if (!empty($links)) {
        // Check if the URL starts with "http://" and change it to "https://"
        if (stripos($links, 'http://') === 0) {
            $links = 'https://' . substr($links, 7);
        }

        if (isValidURL($links)) {
            $userIP = $_SERVER['REMOTE_ADDR'];

            $checkSql = "SELECT * FROM links WHERE Link = :links";
            $checkStmt = $PDO->prepare($checkSql);
            $checkStmt->bindParam(':links', $links, PDO::PARAM_STR);
            $checkStmt->execute();

            if ($checkStmt->rowCount() == 0) {
                $sql = "INSERT INTO links (Link, Username, userIP, Date) VALUES (:links, :Username, :userIP, NOW())";
                $stmt = $PDO->prepare($sql);
                $stmt->bindParam(':links', $links, PDO::PARAM_STR);
                $stmt->bindParam(':userIP', $userIP, PDO::PARAM_STR);
                $stmt->bindParam(':Username', $Username, PDO::PARAM_STR);

                if ($stmt->execute()) {
                    $successMessage = "Thanks for the donation!";
                }
            } else {
                $errorMessage = "Error: Link already exists, thanks though!";
            }
        } else {
            $errorMessage = "That's not a valid link";
        }
    }
} else {
    // User has not agreed to the terms
    $errorMessage = "You did not agree to the terms. Once you do, you can add links.";
}

if (isset($errorMessage) || isset($successMessage)) {
    $message = isset($errorMessage) ? $errorMessage : $successMessage;
    $_SESSION["Usermessage"] = $message;
    $_SESSION["Username"] = $Username;
    echo "<script>
                window.history.go(-1); 
                window.location.reload();
          </script>";
}

function isValidURL($url) {
    if (stripos($url, 'http://') === 0) {
        $httpsURL = 'https://' . substr($url, 7); // Change HTTP URL to HTTPS
        $ch = curl_init($httpsURL);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!curl_errno($ch)) {
            curl_exec($ch);
            if (curl_error($ch)) {
                curl_close($ch);
                return false;
            }
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode === 301) {
                $newLocation = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
                curl_close($ch);
                $httpsURL = $newLocation;
                return isValidURL($httpsURL);
            }

            curl_close($ch);
            return $httpCode === 200;
        } elseif (curl_errno($ch) === 35) {
            // Retry with HTTP.
            $httpURL = 'http://' . substr($httpsURL, 8); // Change HTTPS URL to HTTP
            return isValidURL($httpURL);
        } else {
            echo "<script>
            window.history.go(-1); 
            window.location.reload();
            </script>";
            curl_close($ch);
            return false; // Handle other response codes here
        }
    } else {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!curl_errno($ch)) {
            curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if ($httpCode === 301) {
                $newLocation = curl_getinfo($ch, CURLINFO_REDIRECT_URL);
                curl_close($ch);
                $url = $newLocation;
                return isValidURL($url);
            } elseif ($httpCode === 0) {
                echo "<script>
                        alert('Failed to connect to the URL. Please check your internet connection or the target URL.');
                        window.history.go(-1); 
                        window.location.reload();
                    </script>";
                exit;
            }

            curl_close($ch);
            return $httpCode === 200;
        } else {
            echo "cURL error: " . curl_error($ch);
            curl_close($ch);
            return false; // Handle other response codes here
        }
    }
}

function UserAgreement() {
    if (isset($_SESSION['agreed_terms']) && $_SESSION['agreed_terms'] === true) {
        return true; // User has agreed to the terms
    } else {
        return false; // User has not agreed to the terms
    }
}
