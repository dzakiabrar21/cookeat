<?php
include 'includes/header.php';
?>
<style>
    .container{
        display:flex;
        flex-direction: column;
        height:90vh;
        width:40%;
        justify-content: center;
        align-items: center; 
        background-position: center;
    }
    .a{ 
        height:100%;
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; 
        background-position: center;
    }
    .b{
        width:100%   ;
        /* padding:25%;
        padding-top:5px;
        padding-bottom:5px; */
        margin:0;
        margin-bottom:5px;
        height:auto;
        border-bottom: 7px solid rgba(196, 150, 114, 0.78);
        border-top: 7px solid rgba(196, 150, 114, 0.78);
        border-radius:20px;
        display: inline-block;
        display:flex;
        flex-direction: column;
        justify-content: center;
        align-items: center; 
        background-position: center;
    }
    .coba img {
        height: 50vh;
        aspect-ratio: 4 / 4;
    }
    .coba {
        height: 60vh;   
        max-height: 60vh;
        aspect-ratio: 4 / 4;    
        display: flex;
        background-color:rgb(148, 113, 86);
        align-items: center;
        justify-content: center; 
    }
    .h2{
        background-color:rgba(214, 182, 122, 0.89);
        padding-left:14px;
        padding-right:14px;
        padding-top:5px;
        padding-bottom:5px;
        border-radius:15px;
        /* color:rgba(196, 150, 114, 0.78); */
        color:rgb(255, 255, 255) ;
        font-size:4vh;
    }
    .h1{
        border-radius:10px;
        /* border-top: 5px solid rgba(196, 150, 114, 0.78); */
        /* border-bottom: 5px solid rgba(196, 150, 114, 0.78); */
        color: #744928;
        font-size:7vh;
        /* padding:10%;
        padding-top:5px;
        padding-bottom:5px;
        margin-bottom:5px; */
    }
</style>
<div class="a">
    <div class="b">
        <h1 class="h1"><strong>COOKEAT</strong></h1>
        <h2 class="h2"><strong>Cook it & Eat it</strong></h2>
    </div>
    <div class="coba rounded-circle">
        <img src="assets/uploads/cookie.jpg" class="rounded-circle" alt="Example" draggable="false">
    </div>
</div>
<?php
include 'includes/footer.php';
?>