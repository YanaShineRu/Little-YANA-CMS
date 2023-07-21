<?php
session_start();

// Check if the administrator is authorized
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin.php");
    exit();
}

// Check if there is an id parameter in the URL
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: admin.php");
    exit();
}

// Get current posts from the file
$posts = json_decode(file_get_contents('blog/posts.json'), true);

// Check if the post with the specified id exists
$id = $_GET['id'];
if (!isset($posts[$id])) {
    header("Location: admin.php");
    exit();
}

// Get the current date
$date = date('d.m.Y');

// Process post editing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    // Get current posts from the file
    $posts = json_decode(file_get_contents('blog/posts.json'), true);

    // Check if the post with the specified id exists
    $id = $_GET['id'];
    if (!isset($posts[$id])) {
        header("Location: admin.php");
        exit();
    }

    $posts[$id]['date'] = date('d.m.Y');
    $posts[$id]['title'] = $title;
    $posts[$id]['content'] = $content;

    // Process image loading
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageFile = $_FILES['image']['tmp_name'];
        $imageFileName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';
        $uploadPath = $uploadDir . $imageFileName;

        // Move the uploaded image to the "uploads" folder
        move_uploaded_file($imageFile, $uploadPath);

        // Update the path to the image in the post
        $posts[$id]['image'] = $uploadPath;
    }

    // Save updated posts to a file
    file_put_contents('blog/posts.json', json_encode($posts));

    // Redirect to admin panel after editing a post
    header("Location: admin.php");
    exit();
}

// Get data for editing
$id = $_GET['id'];
$post = $posts[$id];
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="shortcut icon" href="favicon-admin.ico" type="image/x-icon">
    <title>Edit post: <?php echo htmlspecialchars($post['title']); ?></title>
    <link rel="stylesheet" type="text/css" href="css/admin.css">
</head>
<body>
    <div class="header">
        <h1>Edit post: <?php echo htmlspecialchars($post['title']); ?></h1>
    </div>
    <div class="container">
        <div class="form-container">
            <form method="post" action="edit_post.php?id=<?php echo $id; ?>" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" name="title" id="title" value="<?php echo htmlspecialchars($post['title']); ?>">
                </div>
                <div class="form-group">
                    <label for="content">Content post:</label>
                    <textarea name="content" id="content"><?php echo htmlspecialchars($post['content']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <?php if (isset($post['image']) && !empty($post['image'])): ?>
                        <div class="image-preview">
                            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
                        </div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <a href="admin.php"><span class="back-button">Back</span></a>
                    <input type="submit" value="Save post">
                </div>
            </form>
        </div>
    </div>
</body>
</html>
