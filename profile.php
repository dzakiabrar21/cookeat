<?php
include 'includes/header.php';
include 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
}

if (isset($_SESSION['profile'])) {
    if( $_SESSION['profile'] ){
        $_SESSION['profile'] = 0;
        header("Location: profile.php");
        exit;
    }
} 

$error = "";

if(isset($_GET['username'])){
    $user_id = $_GET['username'];
    $stmt = $pdo->prepare("SELECT user_id 
        FROM User 
        join photo p on user.photo_id = p.photo_id
        WHERE username = ?");
    $stmt->execute([$user_id]);
    $temp = $stmt->fetch();
    if ($temp) {
        $user_id = $temp["user_id"];
    }
}
else{
    $user_id = $_SESSION['user_id'];
}

// Update profile
if (isset($_POST['update_profile'])) {
    $_SESSION['profile'] = 1;
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];
    $stmt = $pdo->prepare("select user_id from user WHERE username = ?");
    $stmt->execute([$username]);
    $temp = $stmt->fetch();
    if($temp && $_SESSION['username'] != $username) {$error="cannot update username. username is already taken"; $username = $_SESSION["username"];}
    $stmt = $pdo->prepare("UPDATE User SET username = ?, full_name = ?, bio = ? WHERE user_id = ?");
    $stmt->execute([$username, $full_name, $bio, $user_id]);
    // header("Location: profile.php");
}

// Delete account
if (isset($_POST['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM comment WHERE post_id = ?");
    $stmt->execute([$_POST['post_id']]);
    $stmt = $pdo->prepare("DELETE FROM media_upload WHERE post_id = ?");
    $stmt->execute([$_POST['post_id']]);
    $stmt = $pdo->prepare("DELETE FROM saved_post WHERE post_id = ?");
    $stmt->execute([$_POST['post_id']]);
    $stmt = $pdo->prepare("DELETE FROM post_like WHERE post_id = ?");
    $stmt->execute([$_POST['post_id']]);
    $stmt = $pdo->prepare("DELETE FROM Post WHERE post_id = ?");
    $stmt->execute([$_POST['post_id']]);
    header("Location: profile.php");
}

// Fetch user data
$stmt = $pdo->prepare("SELECT * 
                    FROM User 
                    join photo p on user.photo_id = p.photo_id
                    WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Fetch user's posts
$stmt = $pdo->prepare("SELECT p.*, ph.photo_url, v.video_url
                       FROM Post p
                       LEFT JOIN Media_Upload mu ON p.post_id = mu.post_id
                       LEFT JOIN Photo ph ON mu.photo_id = ph.photo_id
                       LEFT JOIN Video v ON mu.photo_id = v.video_id
                       WHERE p.user_id = ?");
$stmt->execute([$user_id]);
$posts = $stmt->fetchAll();
?>
<?php if($user): ?>
<h1><?php if ($user["user_id"] == $_SESSION["user_id"]) echo("Your ") ?>Profile:</h1>
<div class="profile-card">
    <div class="profile-header">
        <img src="<?php echo($user['photo_url']); ?>" class="user-profile" alt="User Profile">
        <h2 style="color:#744928;"><?php echo $user['username']; ?></h2>
    </div>
    <div class="desc card">
        <p><strong>Name:</strong> <?php echo $user['full_name']; ?></p>
        <p><strong>Bio:</strong> <?php echo $user['bio']; ?></p>
        <?php if ($user["user_id"] == $_SESSION["user_id"]): ?>
        <button type="button" id="edit" onclick="openedit()">Edit Profile</button>
        <?php 
            if ($error){echo("<div class='warning'>error: ".$error."</div>");} 
        ?>
    </div>

    <div class="card profile-edit hidden" id="profile-edit">
        <h3>Edit Profile</h3>
        <div style="display: flex; gap: 20px; align-items: flex-start;">
            <form method="POST">
                <strong>Username:</strong>
                <input type="text" name="username" value="<?php echo $user['username']; ?>" required>
                <strong>Full Name:</strong>
                <input type="text" name="full_name" value="<?php echo $user['full_name']; ?>">
                <strong>Description:</strong>
                <textarea name="bio"><?php echo $user['bio']; ?></textarea>
                <input type="submit" name="update_profile" value="Update Profile">
            </form>
        </div>
    </div>
    <?php else: ?> </div>
    <?php endif; ?>
</div>

<h3>Posts:</h3>
<div style="display: flex; flex-wrap: wrap; gap: 10px;" >
    <?php $ada = false; foreach ($posts as $post): $ada = true;?>
        <div style="width: 200px;" class="card">
            <a href="post.php?post_id=<?php echo $post['post_id']; ?>">
            <?php if ($post['type_id'] == 1): ?>
                <img src="<?php echo $post['photo_url']; ?>" alt="Post Media" style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
            <?php elseif ($post['type_id'] == 2): ?>
                <video style="width: 100%; height: 150px; object-fit: cover; border-radius: 8px;">
                    <source src="<?php echo $post['video_url']; ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            <?php endif; ?>
            <p style="font-size: 0.8rem; " ><?php echo substr($post['content'], 0, 30) . (strlen($post['content']) > 30 ? '...' : ''); ?></p>
            <?php if ($user["user_id"] == $_SESSION["user_id"]): ?>
            <form method="POST" onsubmit="return confirm('Are you sure you want to delete this post?');" style="display: inline;">
                <input type="hidden" name="post_id" value="<?php echo $post['post_id']; ?>">
                <button type="submit" name="delete"><i class="fas fa-trash"></i> delete post</button>
            </form>
            <?php endif; ?>
            </a>
        </div>
    <?php 
        endforeach;
        if(!$ada){echo("There is no post yet");} 
    ?>
</div>
<script>
    function openedit(){
        const edit = document.getElementById('edit') ;
        const profile = document.getElementById('profile-edit');
        profile.classList.toggle("hidden");
    }
</script>
<?php else: ?> <div class="profile-card" style="text-align: center;"> <h1>profile not found </h1> </div><?php endif; ?>
<?php include 'includes/footer.php'; ?>