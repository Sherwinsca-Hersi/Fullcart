<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback Chat</title>
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
    <div class="banner_rightbar container" id="msg-container">
        <?php 
        $user_id = $_GET['user-id'];
                $user_details = $mysqli->query("SELECT id,name,mobile,email_id 
    FROM `e_user_details` WHERE id='$user_id' AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
            ?>

        <div class="fixed-top-container">
            <div class="fixed_top_cust_data">
                <div><h3><i class="fa fa-solid fa-user" style="color: grey;"></i>&emsp;<?php  echo $user_details['name'];?></h3></div>
            </div>
           <div class="fixed_top_contact">
                <div class="fixed_top_cust_data">
                    <i class="fa fa-solid fa-phone" style="color: #f8fcf8;"></i>
                    <i class="fa fa-solid fa-envelope" style="color: #f7f8f7;"></i>
                 </div>
                <div class="fixed_top_cust_data">
                    <p><?php  echo $user_details['mobile'];?></p>
                    <p><?php  echo $user_details['email_id'];?></p>
                </div>
            </div>
        </div>
        <div class="msg_container">
            <div class="msg_cont_div">
        <?php

            $feedback_msg_query = $mysqli->query("SELECT sender_id, receiver_id, message FROM `e_feedback` 
            WHERE (sender_id = '$user_id' OR receiver_id = '$user_id') AND cos_id = '$cos_id' AND active = 1");

            $feedback_msgs = [];
            while ($feedback_msg_table = $feedback_msg_query->fetch_assoc()) {
                $feedback_msgs[] = $feedback_msg_table;
            }
            
            foreach ($feedback_msgs as $feedback_msg):
                $alignment = $feedback_msg['sender_id'] === $user_id ? 'align-left' : 'align-right';
        ?>
            <div class="user_msg_div <?php echo $alignment; ?>">
                <h4><?php echo $feedback_msg['message']; ?></h4>
            </div> 
        <?php
            endforeach;
        ?>  
    </div>
</div>
<div class="message-input-container">
    <form method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off">
        <input type="text" id="messageInput" class="message-input" name="msg" placeholder="Type your message..." />
        <input type="hidden" name="sender_id" value="<?php echo isset($_GET['user-id']) ? htmlspecialchars($_GET['user-id']) : ''; ?>">
        <input type="hidden" name="receiver_id" value="<?php echo isset($_GET['receiver-id']) ? htmlspecialchars($_GET['receiver-id']) : ''; ?>">
        <input type="submit" id="sendButton" class="send-button" value="Send" name="msg_send">
    </form>
</div>
    </div>
    <?php
            require_once "logoutpopup.php";
        ?>
    <?php
        if(isset($_SESSION['error_message'])): 
        ?>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
        <script>
    iziToast.error({
        title: 'Error',
        message: '<?php echo addslashes($_SESSION['error_message']); ?>',
        position: 'bottomCenter'
    });
    </script>
        <?php
        unset($_SESSION['error_message']);
        endif;
    ?>
    <script>
        window.onload = () => {
        const messagesContainer = document.getElementById('msg-container');
        messagesContainer.scrollTo({
        top: messagesContainer.scrollHeight,
        behavior: 'smooth'
    });
    };
    history.popstate (null, '', window.location.href);
    window.addEventListener("popstate", function() {
        window.location.href = 'feedbackScreen.php';
    });
    </script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>