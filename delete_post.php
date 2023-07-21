<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $posts = json_decode(file_get_contents('blog/posts.json'), true);
    $post_id = $_POST['post_id'];

    // Check if there is a post with this ID
    if (isset($posts[$post_id])) {
        // Delete a post from the array by its ID
        unset($posts[$post_id]);

        // Save the updated array back to the file
        file_put_contents('blog/posts.json', json_encode($posts));

        // Redirect the user back to the admin panel page
        header("Location: admin.php");
        exit();
    }
}
?>