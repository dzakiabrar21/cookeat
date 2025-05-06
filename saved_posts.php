<?php
include 'includes/header.php';
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

$user_id = $_SESSION['user_id'];

// Delete saved post
if (isset($_POST['unsave'])) {
    $post_id = $_POST['post_id'];
    $stmt = $pdo->prepare("DELETE FROM Saved_Post WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    header("Location: saved_posts.php");
}

// Fetch saved posts
$stmt = $pdo->prepare("SELECT p.*, u.username, ph.photo_url, v.video_url, mu.type_id
                       FROM Post p
                       JOIN User u ON p.user_id = u.user_id
                       JOIN Saved_Post sp ON p.post_id = sp.post_id
                       LEFT JOIN Media_Upload mu ON p.post_id = mu.post_id
                       LEFT JOIN Photo ph ON mu.photo_id = ph.photo_id
                       LEFT JOIN Video v ON mu.photo_id = v.video_id
                       WHERE sp.user_id = ?");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>

<h2>Saved Posts</h2>
<div style="display: flex; flex-wrap: wrap; gap: 10px;">
    <?php if(empty($posts)) echo("there is no saved post yet"); ?>
    <?php foreach ($posts as $post): ?>
        <div style="width: 200px;" class="card">
            <a href="post.php?post_id=<?php echo $post['post_id']; ?>" style="text-decoration: none; color: inherit;">
                <?php if ($post['type_id'] == 1): ?>
                    <img src="<?php echo $post['photo_url']; ?>" alt="Post Media" style="width: 100% ; height: 150px; object-fit: cover; border-radius: 8px;">
                <?php elseif ($post['type_id'] == 2): ?>
                    <video style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                        <source src="<?php echo $post['video_url']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php endif; ?>
                <p style="font-size: 0.8rem;"><?php echo substr($post['username'], 0, 30) . (strlen($post['content']) > 30 ? '...' : ''); ?></p>
            </a>
            <form method="POST" onsubmit="return confirm('Are you sure you want to unsave this post?');" style="display: inline;">
                <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                <button type="submit" name="unsave"><i class="fas fa-bookmark"></i> Unsave</button>
            </form>
            
        </div>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>