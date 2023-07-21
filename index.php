<?php
// Start the session to check if the user is logged in
session_start();

$settings = require('settings.php');

// Check if the user is logged in
$loggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" type="image/png" href="icon.png">
    <title><?php echo htmlspecialchars($settings['site_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($settings['site_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($settings['site_keywords']); ?>">
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body>
    <div class="header">
        <h1><?php echo htmlspecialchars($settings['site_title']); ?></h1>
        <div class="nav-button">            
        <?php if (!$loggedIn): ?>
            <a href="admin.php">Login</a>
        <?php else: ?>
            <a href="admin.php">Enter Admin panel</a>
            <a href="logout.php">Logout</a>
        <?php endif; ?>
        </div>
    </div>
    <div class="container">
        <?php
        $posts = json_decode(file_get_contents('blog/posts.json'), true);
        if ($posts) {
            foreach ($posts as $key => $post) {
                echo "<div class='post'>";
                echo "<h2><a href='full_article.php?id=" . $key . "'>" . htmlspecialchars($post['title']) . "</a></h2>";
                
                if (isset($post['image']) && !empty($post['image'])) {
                    echo '<a href="full_article.php?id=' . $key . '"><img src="' . htmlspecialchars($post['image']) . '" alt="' . htmlspecialchars($post['title']) . '" title="' . htmlspecialchars($post['title']) . '"></a>';
                }
                // Truncate the content to the specified length
                $excerpt = $post['content'];
                $maxLength = $settings['excerpt_length'];
                if (mb_strlen($excerpt) > $maxLength) {
                    $excerpt = mb_substr($excerpt, 0, $maxLength);
                    // Find the last space in the truncated excerpt
                    $lastSpacePos = mb_strrpos($excerpt, ' ');
                    // Extract the part before the last space as well as the part after the last space
                    $excerpt = mb_substr($excerpt, 0, $lastSpacePos) . '...';
                }
                echo "<p>" . nl2br(htmlspecialchars($excerpt)) . " <a href='full_article.php?id=" . $key . "'>Read more</a></p>";
                echo "<p class='date'>Posted on: " . htmlspecialchars($post['date']) . " | </p>";
                echo "<p class='author'>Author: " . htmlspecialchars($settings['name_author']) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<p>There are no posts available.</p>";
        }
        ?>
    </div>
</body>
</html>