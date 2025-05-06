<?php
session_start();
$user_id = $_SESSION["user_id"];
include 'config/db.php';

if (isset($_GET['like'])){
    like($_GET['like']);
}
else if (isset($_GET['save'])){
    save($_GET['save']);
}
else if (isset($_GET['search'])){
    load_search($_GET['search']);
}

// Like post
function like($post_id) {
    global $pdo, $user_id;
    // echo("like -> ");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Post_Like  WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $exists = $stmt->fetchColumn();
    // echo($exists);
    if (!$exists){
        // echo("like proses -> ");
        $stmt = $pdo->prepare("INSERT IGNORE INTO Post_Like (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $user_id]);
        $stmt = $pdo->prepare("UPDATE Post SET total_like = total_like + 1 WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $stmt = $pdo->prepare("SELECT total_like FROM post  WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $exists = $stmt->fetchColumn();
        echo($exists);
        // echo("like done -> ");
    } else {
        // echo("unike proses -> ");
        $stmt = $pdo->prepare("DELETE FROM Post_Like WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        $stmt = $pdo->prepare("UPDATE Post SET total_like = total_like - 1 WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $stmt = $pdo->prepare("SELECT total_like FROM post  WHERE post_id = ?");
        $stmt->execute([$post_id]);
        $exists = $stmt->fetchColumn();
        echo($exists);
    }
}

function save($post_id) {
    global $pdo, $user_id;
    echo("save -> ");
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM Saved_Post WHERE post_id = ? AND user_id = ?");
    $stmt->execute([$post_id, $user_id]);
    $exists = $stmt->fetchColumn();
    if(!$exists){
        echo("save proses -> ");
        $stmt = $pdo->prepare("INSERT IGNORE INTO Saved_Post (post_id, user_id) VALUES (?, ?)");
        $stmt->execute([$post_id, $user_id]);
        echo("save done -> ");
    } else{
        echo("unsave proses -> ");
        $stmt = $pdo->prepare("DELETE FROM Saved_Post WHERE post_id = ? AND user_id = ?");
        $stmt->execute([$post_id, $user_id]);
        echo("unsave done -> ");
    }
}

// Add comment
if (isset($_POST['comment'])) {
    $post_id = $_POST['post_id'];
    $msg = $_POST['msg'];
    $stmt = $pdo->prepare("INSERT INTO Comment (post_id, user_id, msg) VALUES (?, ?, ?)");
    $stmt->execute([$post_id, $user_id, $msg]);
    $stmt = $pdo->prepare("UPDATE Post SET total_comment = total_comment + 1 WHERE post_id = ?");
    $stmt->execute([$post_id]);
}

function load_search($x){
    global $pdo, $user_id;
    
    if(!empty($x)){
    // echo($x);
        $searchTerm = '%' . $x . '%';
        $stmt = $pdo->prepare("SELECT u.username, p.photo_url
            FROM user u
            JOIN photo p ON u.photo_id = p.photo_id
            WHERE username LIKE ?");

        $stmt->execute([$searchTerm]);
        $temp = $stmt->fetchAll();
        // echo($x);
        if(empty($temp)) echo("No Acount Found");
        else{
            $counter = 0;
            echo('<strong><h3>Result:</h3></strong>');    
            foreach($temp as $o){
                if ($counter >= 10) {
                    break;
                }
                echo('
                    <a href="profile.php?username='.htmlspecialchars($o["username"]).'" class="account">
                        <div class="cart">
                            <div class="img">
                                <img src="'.htmlspecialchars($o["photo_url"]).'" alt="">
                            </div>
                            <div class="info">
                                <p class="name">'.htmlspecialchars($o["username"]).'</p>
                            </div>
                        </div>
                    </a>
                ');
                $counter++;
            }
        }
    }else echo("
        <div class='desc'>
            <h4>Recent</h4>
            <p><a href='#'>Clear all</a></p>
        </div>
    ");
}

?>