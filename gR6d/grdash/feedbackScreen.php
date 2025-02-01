<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Messages</title>
    <?php 
        require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
</head>
<body>
<?php 
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="banner_rightbar container">
        <h2>Feedback Messages</h2>
        <?php
            if (empty($feedback_users)) {
            ?>
               <div class="not_found_image">
                     <div><img src='../assets/images/noMessages.png' width="80%" height="80%"></div>
               </div>
            <?php 

            }
            else
            {
                $i=1;
                foreach($feedback_users as $feedback_user):
            ?>
                <a href="com_ins_upd.php?user-id=<?php echo  $feedback_user['sender_id'];?>&&receiver-id=<?php echo  $feedback_user['receiver_id'];?>">
                    <div class="user_card">
                        <div class="user_div">
                            <h4><?php echo  $feedback_user['name'];?></h4>
                            <h5><?php echo  $feedback_user['mobile'];?></h5>
                        </div>
                        <div class="view_msg_sect">
                        
                            <!-- <form  method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off">
                                <input type="hidden" name="sender_id" value="<?php echo  $feedback_user['sender_id'];?>">
                                <input type="hidden" name="receiver_id" value="<?php echo  $feedback_user['receiver_id'];?>">
                                <input type="submit" name="view_msg" class="view_msg export_btn" value="View Messages">
                            </form> -->
                            <?php 
                                $sender_id= $feedback_user['sender_id'];
                                $unread_msg = $mysqli->query("SELECT id,message,is_read FROM `e_feedback` WHERE sender_id='$sender_id' 
                                AND  is_read=0 AND cos_id = '$cos_id' AND active = 1")->num_rows;
                                if($unread_msg!=0){
                            ?>
                                <div class="unread_msg_bubble"><?php echo $unread_msg; ?></div>
                            <?php 
                                }
                            ?>
                        </div>
                    </div>
                </a>
            <?php
                $i++;
                endforeach;
           
            }
            ?>
       
    </div>
<div>
    <?php
        require_once "logoutpopup.php";
    ?>
</div>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="../assets/js/common_script.js"></script>
<?php 
    require 'footer.php';
?>
<script>
 window.addEventListener("pageshow", function(event) {
      if (event.persisted) {
        window.location.reload();
      }
    });
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>