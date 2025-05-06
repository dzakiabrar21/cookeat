<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>COOKEAT</title>
    <link rel="icon" href="assets/uploads/cookie.png">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" >
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="sidebar-left atas">
        <h1 class="logo">
            <div style="background-color: #926949 ;">
            COOKEAT
            <img  width="35" height="35" class="rounded-circle" src="https://media.istockphoto.com/id/517109442/photo/chocolate-chip-cookie-isolated.jpg?s=612x612&w=0&k=20&c=RgZOYwzVRTXnIBy8zSkXK-wJfNBy9w023UGULkbH_VE=">
            </div>
        </h1> 
        <hr>
        <nav>
            <?php if (isset($_SESSION['user_id'])): ?>
                <a href="index.php"><i class="fas fa-home"></i> Home</a>
                <a href="#" id="search_icon">
                    <i class="fas fa-search"></i>
                    <span class="d-none d-lg-block search">Search </span>
                </a>
                <a href="saved_posts.php"><i class="far fa-bookmark"></i> Saved</a>
                <a href="create_post.php"><i class="fas fa-plus-square"></i> Create</a>
                <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
                <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <?php else: ?>
                <a href="login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
                <a href="register.php"><i class="fas fa-user-plus"></i> Register</a>
            <?php endif; ?>
        </nav>
        
        <div id="search" class="search_section hidden">
            <h2>Search</h2>
            <form onsubmit="return false;">
                <input type="text" placeholder="Search" name="search" id="search">
            </form>
            <div class="find hidden" id ="find">
            </div>
        </div>
        <script>
            document.getElementById('search_icon').addEventListener('click', function() {
                const searchSection = document.getElementById('search');
                searchSection.classList.toggle('show');
                searchSection.classList.toggle('hidden');
            });
        </script>
    </div>
    
    <!-- <div style="display:flex;">  -->
    <div class="main-content">
        <!-- <div style="min-width:200px;" class="h"></div> -->
        <div class="container">

        