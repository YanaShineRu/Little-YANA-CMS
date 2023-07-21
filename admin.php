<?php
// Start the session to check if the user is logged in
session_start();

$settings = require('settings.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $credentials = include 'password.php';
    if ($username === $credentials['username'] && password_verify($password, $credentials['password'])) {
        $_SESSION['loggedin'] = true;
        header("Location: admin.php");
        exit();
    } else {
        $message = "Incorrect credentials!";
    }
}

// If the user is not authorized, show the login form
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <link rel="shortcut icon" href="favicon-admin.ico" type="image/x-icon">
        <title>Login to admin panel</title>
        <link rel="stylesheet" type="text/css" href="css/admin.css">
    </head>
    <body>
        <div class="header">
            <h1>Login to admin panel</h1>
            <div class="nav-button">
                <a href="index.php">Back home</a>
            </div>
        </div>
        <div class="content">
            <form method="post" action="admin.php" class="form-container">
                <?php if (isset($message)) { echo "<p class='error'>" . $message . "</p>"; } ?>
                <div class="form-group">
                    <label for="username">Login:</label>
                    <input type="text" name="username" id="username">
                    <label for="password">Password:</label>
                    <input type="password" name="password" id="password">
                </div>
                <div class="form-group">
                    <input type="submit" value="Login">
                </div>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon-admin.ico" type="image/x-icon">
    <title>Admin panel</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <div class="header">        
        <h1><?php echo htmlspecialchars($settings['name_author']); ?>, welcome to the Admin panel!</h1>
        <div class="nav-button">            
            <a href="index.php">Back Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </div>
    <div class="container">
        <div class="content">
            <h2>List of posts</h2>
            <ul class="post-list">
                <?php
                $posts = json_decode(file_get_contents('blog/posts.json'), true);
                if ($posts) {
                    foreach ($posts as $key => $post) {
                        echo "<li class='post-item'>";
                        echo "# " . $key . "";
                        echo "<a href='edit_post.php?id=" . $key . "'>" . htmlspecialchars($post['title']) . "</a>";
                        echo "<div class='post-buttons'>";
                        echo "<a href='edit_post.php?id=" . $key . "' class='edit-button'>Edit post</a>";
                        echo "<form method='post' action='delete_post.php'>";
                        echo "<input type='hidden' name='post_id' value='" . $key . "'>";
                        echo "<input type='submit' value='Delete post' class='delete-button'>";
                        echo "</form>";
                        echo "</div>";
                        echo "</li>";
                    }
                } else {
                    echo "<p>There are no posts available.</p>";
                }
                ?>
            </ul>
            <div class="add-post-button">
                <a href="add_post.php">Add a new post</a>
            </div>
        </div>
    </div>
</body>
</html>