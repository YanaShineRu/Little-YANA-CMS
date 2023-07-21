<?php
session_start();

$settings = require('settings.php');

// Check if the administrator is authorized
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Get current posts from the file
    $posts = json_decode(file_get_contents('blog/posts.json'), true);

    // Get the current date
    $date = date('d.m.Y');

    // Create a new post
    $newPost = [
        'title' => $title,
        'content' => $content,
        'date' => $date
    ];

    // Handle image upload
    $targetDir = "uploads/";

    // Create the "uploads" directory if it doesn't exist
    if (!is_dir($targetDir)) {
        mkdir($targetDir);
    }

    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
        // Update the path to the image in the new post
        $newPost['image'] = $targetFile;
    } else {
        // Handle the case of failed file upload (optional)
        // You can set an error message here and display it on the form
    }

    // Add a new post to the array
    $posts[] = $newPost;

    // Save updated posts to a file
    file_put_contents('blog/posts.json', json_encode($posts));

    // Redirect to admin panel after adding a post
    header("Location: admin.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon-admin.ico" type="image/x-icon">
    <title>Add a new post</title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <div class="header">
        <h1><?php echo htmlspecialchars($settings['name_author']); ?>, add a new post</h1>        
    </div>
    <div class="container">
        <div class="form-container">
            <form method="post" action="add_post.php" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title">
                </div>
                <div class="form-group">
                    <label for="content">Content post:</label>
                    <textarea name="content" id="content" rows="8"></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Upload Image:</label>
                    <input type="file" name="image" id="image">
                </div>
                <div class="form-group">
                    <a href="admin.php"><span class="back-button">Back</span></a>
                    <input type="submit" value="Add post">
                </div>
            </form>
        </div>
    </div>
</body>
</html>