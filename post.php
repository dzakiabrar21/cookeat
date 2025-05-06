<?php
include 'includes/header.php';
include 'config/db.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['post_id'])) {
    header("Location: index.php");
}

$post_id = $_GET['post_id'];
$user_id = $_SESSION['user_id'];
$error_message = '';

// Add comment
if (isset($_POST['comment'])) {
    $msg = $_POST['msg'];
    $stmt = $pdo->prepare("INSERT INTO Comment (post_id, user_id, msg) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $msg]);
    $stmt = $pdo->prepare("UPDATE Post SET total_comment = total_comment + 1 WHERE post_id = ?");
    $stmt->execute([$post_id]);
    header("Location: post.php?post_id=$post_id");
}

// Add reply
if (isset($_POST['reply'])) {
    $parent_comment_id = $_POST['parent_comment_id'];
    $msg = $_POST['msg'];

    $stmt = $pdo->prepare("INSERT INTO Comment (post_id, user_id, msg) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $msg]);
    $new_comment_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO Reply (comment_id_1, comment_id_2) VALUES (?, ?)");
    $stmt->execute([$parent_comment_id, $new_comment_id]);

    $stmt = $pdo->prepare("UPDATE Post SET total_comment = total_comment + 1 WHERE post_id = ?");
    $stmt->execute([$post_id]);
    header("Location: post.php?post_id=$post_id");
}



// Delete post
if (isset($_POST['delete_post'])) {
    $stmt = $pdo->prepare("DELETE FROM Post_Like WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $stmt = $pdo->prepare("DELETE FROM Saved_Post WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $stmt = $pdo->prepare("DELETE FROM Reply WHERE comment_id_1 IN (SELECT comment_id FROM Comment WHERE post_id = ?)");
    $stmt->execute([$post_id]);
    $stmt = $pdo->prepare("DELETE FROM Comment WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $stmt = $pdo->prepare("DELETE FROM Media_Upload WHERE post_id = ?");
    $stmt->execute([$post_id]);
    $stmt = $pdo->prepare("DELETE FROM Post WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    header("Location: index.php");
}

// Fetch post details
$stmt = $pdo->prepare("SELECT p.*, u.username, ph.photo_url, v.video_url, mu.type_id
                       FROM Post p
                       JOIN User u ON p.user_id = u.user_id
                       LEFT JOIN Media_Upload mu ON p.post_id = mu.post_id
                       LEFT JOIN Photo ph ON mu.photo_id = ph.photo_id
                       LEFT JOIN Video v ON mu.photo_id = v.video_id
                       WHERE p.post_id = ?");
$stmt->execute([$post_id]);
$post = $stmt->fetch();

// Check if user has liked the post
$stmt = $pdo->prepare("SELECT * FROM Post_Like WHERE post_id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$has_liked = $stmt->fetch();

// Check if user has saved the post
$stmt = $pdo->prepare("SELECT * FROM Saved_Post WHERE post_id = ? AND user_id = ?");
$stmt->execute([$post_id, $user_id]);
$has_saved = $stmt->fetch();

// Fetch comments and replies
$stmt = $pdo->prepare("SELECT c.*, u.username, 
                       TIMESTAMPDIFF(DAY, c.created_at, NOW()) as days_ago
                       FROM Comment c
                       JOIN User u ON c.user_id = u.user_id
                       WHERE c.post_id = ?");
$stmt->execute([$post_id]);
$comments = $stmt->fetchAll();

$replies = [];
$ischild = [];
foreach ($comments as $comment) {
    $stmt = $pdo->prepare("SELECT c.*, u.username, u.user_id,
                           TIMESTAMPDIFF(DAY, c.created_at, NOW()) as days_ago
                           FROM Comment c
                           JOIN User u ON c.user_id = u.user_id
                           JOIN Reply r ON c.comment_id = r.comment_id_2
                           WHERE r.comment_id_1 = ?");
    $stmt->execute([$comment['comment_id']]);
    
    $temp = $stmt->fetchAll();
    $replies[$comment['comment_id']] = $temp;
    // $replies[$comment['comment_id']] = $stmt->fetchAll();
    // $temp = $stmt->fetchAll();
    foreach($temp as $o){
        echo($o["comment_id"]." ");
        $ischild[$o["comment_id"]] = true;
    }
}

// Count replies for each comment
$reply_counts = [];
foreach ($comments as $comment) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as reply_count FROM Reply WHERE comment_id_1 = ?");
    $stmt->execute([$comment['comment_id']]);
    $reply_counts[$comment['comment_id']] = $stmt->fetch()['reply_count'];
}
?>

<div class="feed card">
    <div class="post">
        <div class="post-header">
            <h3>
                <a href="profile.php?username=<?php echo $post['username']; ?>"><?php echo $post['username']; ?></a>
            </h3>
            <span class="post-time"><?php echo date('M j', strtotime($post['created_at'])); ?></span>
        </div>
        <?php if ($post['type_id'] == 1): ?>
            <img src="<?php echo $post['photo_url']; ?>" alt="Post Media">
        <?php elseif ($post['type_id'] == 2): ?>
            <video controls>
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
        <div class="post-content">
            <p><strong><?php echo $post['username']; ?></strong> <?php echo $post['content']; ?></p>
        </div>
    </div>
    <h3>Comments</h3>
    <div class="comment-list">
        <?php if(empty($comments)) echo("there is no comment yet")?>
        <?php foreach ($comments as $comment): ?>
            <?php 
                // if ($child[$comment["comment_id"]] == true):
                // echo("comment_id = ". $comment["comment_id"]." "); 
                // echo("child = ". $child[$comment["comment_id"]]); 
                // echo "child = ".isset($child[$comment["comment_id"]]);
                // echo $comment["comment_id"];
                // echo (isset($child[$comment["comment_id"]]) );
                if(isset($ischild[$comment["comment_id"]]) ){
                    if($ischild[$comment["comment_id"]]==true) continue;
                }
            ?>
            <div class="comment">
                <p>
                    <strong><a href="profile.php?username=<?php echo $comment['username']; ?>"><?php echo $comment['username']; ?></a></strong> <?php echo $comment['msg']; ?>
                    <span class="comment-meta">
                        <?php echo $comment['days_ago'] . ' days ago'; ?>
                        <a href="#" class="reply-link">Reply</a>
                    </span>
                </p>
                <div class="reply-form" style="display: none;">
                    <form method="POST">
                        <input type="hidden" name="parent_comment_id" value="<?php echo $comment['comment_id']; ?>">
                        <input type="text" name="msg" placeholder="Reply to this comment..." required>
                        <button type="submit" name="reply" style="display: none;"></button>
                    </form>
                </div>
                <?php 
                    if (isset($replies[$comment['comment_id']]) && count($replies[$comment['comment_id']]) > 0): 
                ?>
                    <a href="#" class="view-replies">View replies </a>
                    <div class="replies" style="display: none;">
                        <?php foreach ($replies[$comment['comment_id']] as $reply): ?>
                            <div class="reply">
                                <p>
                                    <strong><a href="profile.php?username=<?php echo $reply['username']; ?>"><?php echo $reply['username']; ?></a></strong> <?php echo $reply['msg']; ?>
                                    <span class="comment-meta">
                                        <?php echo $reply['days_ago'] . 'd'; ?>
                                    </span>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <?php 
                // endif; 
            ?>
        <?php endforeach; ?>
    </div>
</div>


<h3>Add Comment</h3>
<div class="card">
    <form method="POST">
        <textarea name="msg" placeholder="Write a comment..." required></textarea>
        <input type="submit" name="comment" value="Comment">
    </form>
</div>

<script>
document.querySelectorAll('.reply-link').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const replyForm = link.closest('.comment').querySelector('.reply-form');
        replyForm.style.display = replyForm.style.display === 'none' ? 'block' : 'none';
    });
});

document.querySelectorAll('.view-replies').forEach(link => {
    link.addEventListener('click', (e) => {
        e.preventDefault();
        const replies = link.closest('.comment').querySelector('.replies');
        replies.style.display = replies.style.display === 'none' ? 'block' : 'none';
        link.textContent = replies.style.display === 'none' ? 
            'View replies' : 
            'Hide replies';
    });
});
</script>
<script src="assets/js/main.js"></script>

<?php include 'includes/footer.php'; ?>