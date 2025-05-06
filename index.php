<?php
include 'includes/header.php';
include 'config/db.php';


if (!isset($_SESSION['user_id'])) {
    header("Location: gate.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->query("SELECT p.*, u.username, ph.photo_url, v.video_url, mu.type_id
                     FROM Post p
                     JOIN User u ON p.user_id = u.user_id
                     LEFT JOIN Media_Upload mu ON p.post_id = mu.post_id
                     LEFT JOIN Photo ph ON mu.photo_id = ph.photo_id
                     LEFT JOIN Video v ON mu.photo_id = v.video_id
                     ORDER BY p.created_at DESC");
$posts = $stmt->fetchAll();

if (isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $user_id = $_SESSION['user_id'];
    $msg = $_POST['msg'];
    $stmt = $pdo->prepare("INSERT INTO Comment (post_id, user_id, msg) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $msg]);
    $stmt = $pdo->prepare("UPDATE Post SET total_comment = total_comment + 1 WHERE post_id = ?");
    $stmt->execute([$post_id]);
    header("Location: post.php?post_id=$post_id");
}

?>
<div class="feed bawah">
    <?php foreach ($posts as $post): ?>
        <?php
        // Check if user has liked the post
        $stmt = $pdo->prepare("SELECT * FROM Post_Like WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post['post_id'], $user_id]);
        $has_liked = $stmt->fetch();

        // Check if user has saved the post
        $stmt = $pdo->prepare("SELECT * FROM Saved_Post WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post['post_id'], $user_id]);
        $has_saved = $stmt->fetch();
        ?>
        
        <div class="post card">
            <div onclick="post(<?php echo $post['post_id']; ?>)">
                <div class="post-header">
                    <a href="profile.php?username=<?php echo $post['username']; ?>"><h3 style="color:#744928;"><?php echo $post['username']; ?></h3></a>
                    <span class="post-time"><?php echo date('M j', strtotime($post['created_at'])); ?></span>
                </div>
                <?php if ($post['type_id'] == 1): ?>
                    <img src="<?php echo $post['photo_url']; ?>" alt="Post Media">
                <?php elseif ($post['type_id'] == 2): ?>
                    <video controls style="height:auto;">
                        <source src="<?php echo $post['video_url']; ?>" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                <?php else: ?>
                    <p>No media available</p>
                <?php endif; ?>
                <div class="post-actions">
                    <div style="display: inline-flex;" onclick="like(this, <?php echo $post['post_id']; ?>);">
                            <button class="<?php if(!$has_liked){echo("hidden");} ?>" type="button" ><span><i class="fas fa-heart"></i> <?php echo $post['total_like']; ?></span></button>
                            <button class="<?php if($has_liked){echo("hidden");} ?>" type="button" ><span><i class="far fa-heart"></i> <?php echo $post['total_like']; ?> </span></button>
                    </div>    
                    <div style="display: inline-flex;" onclick="save(this, <?php echo $post['post_id']; ?>);">
                        <a href="post.php?post_id=<?php echo $post['post_id']; ?>" >
                            <button type="button"><span><i class="far fa-comment"></i> <?php echo $post['total_comment']; ?></span></button>
                        </a>
                    </div>  
                    <div style="display: inline-flex;" onclick="save(this, <?php echo $post['post_id']; ?>);">
                            <button class="<?php if(!$has_saved){echo("hidden");} ?>" type="button" ><span><i class="fas fa-bookmark"></i></span></button>
                            <button class="<?php if($has_saved){echo("hidden");} ?>" type="button" ><span><i class="far fa-bookmark"></i></span></button>
                    </div>
                </div>
            </div>
            <div class="post-content">
                <p><strong><?php echo $post['username']; ?></strong> <?php echo $post['content']; ?></p>
            </div>
            
            <div class="add-comment">
                <form method="POST">
                    <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                    <input type="text" name="msg" placeholder="Add a comment..." required>
                    <button type="submit" name="comment" style="display: none;"></button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>
<!-- </div> -->

<?php include 'includes/footer.php'; ?>