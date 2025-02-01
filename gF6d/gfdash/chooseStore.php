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
        }
        .choose_btn_div{
            display: flex;
        }
        h1 { font-size: 50px; margin-top: 50px; }
        .btn { padding: 15px 30px; font-size: 20px; margin: 20px; cursor: pointer; }
    </style>
</head>
<body>

    <div class="choose_section">
        <h1>FullComm</h1>
        <div class="choose_btn_div">
            <button class="btn" onclick="selectStore('1')">Gifting Store</button>
            <button class="btn" onclick="selectStore('2')">Grocery Store</button>
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