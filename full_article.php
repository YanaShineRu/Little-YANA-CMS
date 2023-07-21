<?php
session_start();

$settings = require('settings.php');

// Check if there is an id parameter in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php");
    exit();
}

// Get current posts from the file
$posts = json_decode(file_get_contents('blog/posts.json'), true);

// Check if the post with the specified id exists
$id = $_GET['id'];
if (!isset($posts[$id])) {
    header("Location: index.php");
    exit();
}

$post = $posts[$id];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon-admin.ico" type="image/x-icon">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
    </div>
    <div class="container">
        <div class="post">
            <?php if (isset($post['image']) && !empty($post['image'])): ?>                
                    <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>" title="<?php echo htmlspecialchars($post['title']); ?>">
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
            <p class="date-author">Posted on <?php echo htmlspecialchars($post['date']); ?> | Author: <?php echo htmlspecialchars($settings['name_author']); ?></p>
            <p class="back-link"><a href="index.php">Back home</a></p>
        </div>
    </div>
</body>
</html>