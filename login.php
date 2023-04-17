<?php
require_once 'config.php';

// Initialize variables to store form input errors
$login_error = "";
$register_error = "";
$register_success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Check if the login form is submitted
    if (isset($_POST["action"]) && $_POST["action"] == "login")
    {
        // Get email and password from the submitted form
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Prepare and bind SQL query
        $stmt = $conn->prepare("SELECT customer_id, password FROM customers WHERE email = :email");
        $stmt->bindValue(":email", $email, PDO::PARAM_STR);

        // Execute query and get results
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);


        if ($stmt->rowCount() > 0)
        {
            // Get user data
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify password
            if (password_verify($password, $user['password']))
            {
                // Start a new session and set session variables
                session_start();
                $_SESSION['customer_id'] = $user['customer_id'];
                $_SESSION['email'] = $email;

                // Redirect to a protected page
                header("Location: index.php");
            } else
            {
                // Incorrect password
                $login_error = "Invalid email or password.";
            }
        }
        else
        {
            $login_error = "Invalid email or password.";
        }

        // Close connection and statement
        $stmt = null;
        $conn = null;

        // Check if the registration form is submitted
    } elseif (isset($_POST["action"]) && $_POST["action"] == "register") {
        // Get user input from the submitted form
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare and bind SQL query
        $stmt = $conn->prepare("INSERT INTO customers (first_name, last_name, email, password, account_number) VALUES (:first_name, :last_name, :email, :hashed_password, :account_number)");
        $stmt->bindParam(":first_name", $first_name);
        $stmt->bindParam(":last_name", $last_name);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":hashed_password", $hashed_password);
        $stmt->bindParam(":account_number", $account_number);

        // Set account number to a random value between 100000 and 999999
        $account_number = rand(100000, 999999);

        // Execute query and check for success
        if ($stmt->execute()) {
            $register_success = "Registration successful! <a href='login.php'>Login here</a>";
        } else {
            if ($stmt->errno == 1062) { // Duplicate entry error code
                $register_error = "Error: Email already in use. <a href='login.php'>Try again</a>";
            } else {
                $register_error = "Error: " . $stmt->error . ". <a href='login.php'>Try again</a>";
            }
        }

        // Close connection and statement
        $stmt = null;
        $conn = null;

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="login" style="margin-top: 100px;">
    <?php
    // Check if the user is logged in
    if (isset($_SESSION['customer_id'])) {
        // Get the user's ID from the session
        $customer_id = $_SESSION['customer_id'];

        // Prepare and bind SQL query to retrieve user information
        $stmt = $conn->prepare("SELECT first_name, last_name, email FROM customers WHERE customer_id = ?");
        $stmt->bind_param("i", $customer_id);

        // Execute query and get results
        $stmt->execute();
        $result = $stmt->get_result();

        // Fetch the user's information
        $user = $result->fetch_assoc();
        ?>
        <div class="user-card">
            <h2>Welcome, <?php echo $user['first_name'] . ' ' . $user['last_name']; ?>!</h2>
            <p>Email: <?php echo $user['email']; ?></p>
        </div>
    <?php
    } else {
    // Display the login and registration forms if the user is not logged in
    ?>
    <h1>Login</h1>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="login">
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
    <?php if (!empty($login_error)): ?>
        <p><?php echo $login_error; ?></p>
    <?php endif; ?>

    <h1>Register</h1>
    <form action="login.php" method="post">
        <input type="hidden" name="action" value="register">
        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" id="first_name" required>
        <br>
        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" id="last_name" required>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <input type="submit" value="Register">
    </form>
    <?php if (!empty($register_error)): ?>
        <p><?php echo $register_error; ?></p>
    <?php endif; ?>
    <?php if (!empty($register_success)): ?>
        <p><?php echo $register_success; ?></p>
    <?php endif; ?>
    <?php } ?>
</main>
<?php include 'footer.php'; ?>
</body>
</html>