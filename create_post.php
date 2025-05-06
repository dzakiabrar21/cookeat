<?php
include 'includes/header.php';
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];

    // Validasi bahwa field content dan type_id ada
    if (!isset($_POST['content']) || !isset($_POST['type_id'])) {
        $error_message = "Please fill in all required fields.";
    } else {
        $content = $_POST['content'];
        $type_id = $_POST['type_id'];

        if (!empty($_FILES['media']['name'])) {
            $allowed_image_types = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $allowed_video_types = ['video/mp4', 'video/webm', 'video/ogg'];
            $file_type = $_FILES['media']['type'];

            // Validasi ukuran file (maksimal 5MB)
            $max_file_size = 50 * 1024 * 1024; // 5MB dalam bytes
            if ($_FILES['media']['size'] > $max_file_size) {
                $error_message = "File size exceeds 50MB limit.";
            } elseif ($_FILES['media']['error'] !== UPLOAD_ERR_OK) {
                $error_message = "Error uploading file. Please try again.";
            } elseif ($type_id == 1 && !in_array($file_type, $allowed_image_types)) {
                $error_message = "Only JPEG, PNG, or GIF images are allowed for images.";
            } elseif ($type_id == 2 && !in_array($file_type, $allowed_video_types)) {
                $error_message = "Only MP4, WebM, or OGG videos are allowed for videos.";
            } else {
                $stmt = $pdo->prepare("INSERT INTO Post (user_id, type_id, content) VALUES (?, ?, ?)");
                $stmt->execute([$user_id, $type_id, $content]);
                $post_id = $pdo->lastInsertId();

                if ($type_id == 1) { // Photo
                    $photo_name = $_FILES['media']['name'];
                    $photo_url = "assets/uploads/" . $photo_name;
                    move_uploaded_file($_FILES['media']['tmp_name'], $photo_url);

                    $stmt = $pdo->prepare("INSERT INTO Photo (photo_name, photo_url) VALUES (?, ?)");
                    $stmt->execute([$photo_name, $photo_url]);
                    $photo_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO Media_Upload (photo_id, post_id, type_id) VALUES (?, ?, ?)");
                    $stmt->execute([$photo_id, $post_id, $type_id]);
                } elseif ($type_id == 2) { // Video
                    $video_name = $_FILES['media']['name'];
                    $video_url = "assets/uploads/" . $video_name;
                    move_uploaded_file($_FILES['media']['tmp_name'], $video_url);

                    $stmt = $pdo->prepare("INSERT INTO Video (video_name, video_url) VALUES (?, ?)");
                    $stmt->execute([$video_name, $video_url]);
                    $video_id = $pdo->lastInsertId();

                    $stmt = $pdo->prepare("INSERT INTO Media_Upload (photo_id, post_id, type_id) VALUES (?, ?, ?)");
                    $stmt->execute([$video_id, $post_id, $type_id]);
                }

                header("Location: index.php");
                exit();
            }
        } else {
            $error_message = "Please upload a file.";
        }
    }
}
?>

<h2>Create Post</h2>
<div class="card">
    <?php if ($error_message): ?>
        <p style="color: red; text-align: center;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <textarea name="content" placeholder="Write about your food..." required></textarea>
        <select name="type_id" required>
            <option value="" disabled selected>Select media type</option>
            <option value="1">Photo</option>
            <option value="2">Video</option>
        </select>
        <input type="file" name="media" accept="image/jpeg,image/png,image/gif,video/mp4,video/webm,video/ogg" required>
        *max media 50mb
        <input type="submit" value="Post">
    </form>
</div>

<?php include 'includes/footer.php'; ?>