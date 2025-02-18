<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullComm</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body { 
            text-align: center; 
            font-family: Arial, sans-serif; 
        }
        .choose_section{
            width: 95vw;
            height: 80vh;
            display: grid;
            justify-content: center;
            align-items:center;
            padding: 3em;
        }
        .choose_btn_div{
            display: flex;
            justify-content: center;
            gap: 10em; 
            flex-wrap: wrap; 
            cursor: pointer;
            margin-top: 2em;
        }
        .choose_btn_div .grid_align:hover{
            border-radius: 2em;
            background-color:rgb(238, 214, 248);
            transition: background-color 0.3s ease, transform 0.2s ease;
            transform: scale(1.1);
        }
        .grid_align{
            display: grid;
            justify-content: center;
            gap: 3em;
            padding-bottom: 1em;
        }
    

        h1 { font-size: 5em; margin-top: 50px; color: #770FA0}
        h2 { font-size: 2em; margin-top: 50px; color: #770FA0}
        .btn { padding: 15px 30px; font-size: 20px; margin: 20px; cursor: pointer; }
    </style>
    <?php
        require '../api/header.php';    
    ?>
</head>
<body>

    <div class="choose_section">
        <h1>FullComm</h1>
        <div class="choose_btn_div">
            <div class="grid_align">
                <img src="../assets/images/gifting_store.png" onclick="selectStore('1')" alt="grocery-img" width="300px" height="300px">
                <h2>Gifting Store</h2>
            </div>
            <div class="grid_align">
                <img src="../assets/images/grocery_store.png" onclick="selectStore('2')" alt="gifting-img" width="300px" height="300px">
                <h2>Grocery Store</h2>
            </div>
        </div>
    </div>

    <script>
        function selectStore(storeType) {
            $.ajax({
                url: "fetch_cos_id.php",
                type: "POST",
                data: { 
                    type: storeType,
                    mobile : localStorage.getItem("mobile"),
                    password : localStorage.getItem("password"),
                },
                success: function(response) {
                    let data = JSON.parse(response);
                    if (data.success) {
                        localStorage.setItem("cos_id", data.cos_id);
                        localStorage.setItem("store_type", storeType);
                        localStorage.setItem("authId", data.auth_id);
                        localStorage.setItem('sessionActive',true)
                        if(storeType==1){
                            window.location.href = "dashboard.php";
                        }else{
                            window.location.href = "../../gR6d/grdash/dashboard.php";
                        }
                    } else {
                        alert("Error: " + data.message);
                    }
                }
            });
        }
    </script>

</body>
</html>