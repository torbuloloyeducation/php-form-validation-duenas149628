<?php
$name = $email = $gender = $comment = $website = $phone = $password = $confirm_password = "";
$nameErr = $emailErr = $genderErr = $websiteErr = $phoneErr = $passwordErr = $confirm_passwordErr = $termsErr = "";
$submissionAttempts = 0;

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (isset($_POST['submission_counter']) && is_numeric($_POST['submission_counter'])) {
        $submissionAttempts = (int)$_POST['submission_counter'] + 1;
    } else {
        $submissionAttempts = 1;
    }
    
    if (empty($_POST["name"])) {
        $nameErr = "Name is required";
    } else {
        $name = test_input($_POST["name"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $name)) {
            $nameErr = "Only letters and white space allowed";
        }
    }
    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    if (empty($_POST["gender"])) {
        $genderErr = "Gender is required";
    } else {
        $gender = test_input($_POST["gender"]);
        if ($gender == "Other") {
            $genderErr = "Only choose between Male or Female";
            $gender = "";
        }
    }
    
    if (!empty($_POST["comment"])) {
        $comment = test_input($_POST["comment"]);
    }

    if (!empty($_POST["website"])) {
        $website = test_input($_POST["website"]);
        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            $websiteErr = "Invalid URL format";
        }
    }

    if (empty($_POST["phone"])) {
        $phoneErr = "Phone number is required";
    } else {
        $phone = test_input($_POST["phone"]);
        if (!preg_match('/^[\+]?[0-9\-\s]{7,15}$/', $phone)) {
            $phoneErr = "Invalid phone format. Use digits, spaces, dashes, or a leading + (7-15 characters)";
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "Password is required";
    } else {
        $password = test_input($_POST["password"]);
        if (strlen($password) < 8) {
            $passwordErr = "Password must be at least 8 characters long";
        }
    }

    if (empty($_POST["confirm_password"])) {
        $confirm_passwordErr = "Please confirm your password";
    } else {
        $confirm_password = test_input($_POST["confirm_password"]);
        if ($password !== $confirm_password) {
            $confirm_passwordErr = "Passwords do not match";
        }
    }

    if (!isset($_POST["terms"])) {
        $termsErr = "You must agree to the terms and conditions";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>PHP Form Validation - Complete Lab</title>
<style>
    body {
        font-family: Arial, sans-serif;
        max-width: 600px;
        margin: 20px auto;
        padding: 20px;
        background-color: #f4f4f4;
    }
    .container {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .error {
        color: red;
        font-size: 0.9em;
        margin-left: 10px;
    }
    input[type="text"], input[type="email"], input[type="tel"], 
    input[type="url"], input[type="password"], textarea, select {
        width: 100%;
        padding: 8px;
        margin: 5px 0 10px 0;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }
    input[type="radio"] {
        margin-right: 5px;
    }
    input[type="checkbox"] {
        margin-right: 5px;
    }
    input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        padding: 10px 15px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }
    input[type="submit"]:hover {
        background-color: #45a049;
    }
    .result {
        margin-top: 20px;
        padding: 10px;
        background-color: #e7f3fe;
        border-left: 6px solid #2196F3;
    }
    hr {
        margin: 20px 0;
    }
    .attempt-counter {
        background-color: #f0f0f0;
        padding: 8px 15px;
        border-radius: 4px;
        margin-bottom: 20px;
        text-align: right;
        font-weight: bold;
        border: 1px solid #ddd;
    }
    .form-group {
        margin-bottom: 15px;
    }
    label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
    small {
        color: #666;
        font-size: 0.85em;
        display: block;
        margin-top: 5px;
    }
    h2 {
        color: #333;
        border-bottom: 2px solid #4CAF50;
        padding-bottom: 10px;
    }
    h3 {
        color: #2196F3;
        margin-top: 0;
    }
    .success {
        color: green;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="container">
    <h2>PHP Form Validation Lab</h2>
    
    <div class="attempt-counter">
         Submission Attempt: <?php echo $submissionAttempts; ?>
    </div>
    
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="submission_counter" value="<?php echo $submissionAttempts; ?>">
        
        <div class="form-group">
            <label>Name: <span style="color:red;">*</span></label>
            <input type="text" name="name" value="<?php echo $name; ?>">
            <span class="error"><?php echo $nameErr; ?></span>
        </div>

        <div class="form-group">
            <label>Gender: <span style="color:red;">*</span></label>
            <input type="radio" name="gender" value="Male" <?php if ($gender == "Male") echo "checked"; ?>> Male
            <input type="radio" name="gender" value="Female" <?php if ($gender == "Female") echo "checked"; ?>> Female
            <input type="radio" name="gender" value="Other" <?php if ($gender == "Other") echo "checked"; ?>> Other
            <span class="error"><?php echo $genderErr; ?></span>
        </div>

        <div class="form-group">
            <label>Email: <span style="color:red;">*</span></label>
            <input type="email" name="email" value="<?php echo $email; ?>">
            <span class="error"><?php echo $emailErr; ?></span>
        </div>

        <div class="form-group">
            <label>Phone Number: <span style="color:red;">*</span></label>
            <input type="tel" name="phone" value="<?php echo $phone; ?>" placeholder="e.g., +639123456789 or 091-234-56789">
            <span class="error"><?php echo $phoneErr; ?></span>
            <small>Format: digits, spaces, dashes, or leading + (7-15 characters)</small>
        </div>

        <div class="form-group">
            <label>Website:</label>
            <input type="url" name="website" value="<?php echo $website; ?>" placeholder="www.example.com">
            <span class="error"><?php echo $websiteErr; ?></span>
            <small>(Optional, but must be a valid URL if provided)</small>
        </div>

        <div class="form-group">
            <label>Password: <span style="color:red;">*</span></label>
            <input type="password" name="password">
            <span class="error"><?php echo $passwordErr; ?></span>
            <small>Minimum 8 characters</small>
        </div>

        <div class="form-group">
            <label>Confirm Password: <span style="color:red;">*</span></label>
            <input type="password" name="confirm_password">
            <span class="error"><?php echo $confirm_passwordErr; ?></span>
        </div>

        <div class="form-group">
            <label>Comment:</label>
            <textarea name="comment" rows="3"><?php echo $comment; ?></textarea>
        </div>

        <div class="form-group">
            <input type="checkbox" name="terms" id="terms" value="accepted" <?php if (isset($_POST['terms'])) echo "checked"; ?>>
            <label for="terms" style="display: inline; font-weight: normal;">I agree to the terms and conditions <span style="color:red;">*</span></label>
            <span class="error"><?php echo $termsErr; ?></span>
        </div>
        
        <input type="submit" value="Submit Form">
    </form>
    
    <hr>

    <div class="result">
        <h3>Submitted Information:</h3>
        <?php 
        $hasErrors = !empty($nameErr) || !empty($emailErr) || !empty($genderErr) || 
                     !empty($websiteErr) || !empty($phoneErr) || !empty($passwordErr) || 
                     !empty($confirm_passwordErr) || !empty($termsErr);
        
        if ($_SERVER["REQUEST_METHOD"] == "POST" && !$hasErrors) {
            echo "<p><strong>Name:</strong> " . $name . "</p>";
            echo "<p><strong>Email:</strong> " . $email . "</p>";
            echo "<p><strong>Phone:</strong> " . $phone . "</p>";
            echo "<p><strong>Website:</strong> " . ($website ? $website : "Not provided") . "</p>";
            echo "<p><strong>Gender:</strong> " . $gender . "</p>";
            echo "<p><strong>Comment:</strong> " . ($comment ? nl2br($comment) : "No comment") . "</p>";
            echo "<p><strong>Terms Accepted:</strong> Yes</p>";
            echo "<p><strong>Password:</strong> [Hidden for security]</p>";
            echo "<p class='success'>Form submitted successfully!</p>";
        } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && $hasErrors) {
            echo "<p style='color:red;'><strong>Please correct the errors above and submit again.</strong></p>";
        } else {
            echo "<p>Fill out the form and click submit to see the results.</p>";
        }
        ?>
    </div>
</div>

</body>
</html>