<?php

use App\Models\Team;

require_once "./../boot.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cope</title>
</head>
<body>
    <?= Team::between("id", "14", "16"); ?>
    <Container class="container"></Container>
    <input id="page" type="number" />
    <button onclick="getItem()">Załaduj</button>
    <script>
        let result;
        function getItem() {
            const container = document.getElementsByClassName('container')[0];
            const page = document.getElementById("page").value;
            result = fetch(`http://localhost/phpork/routes/api/index.php?page=${page}`, {method: "POST"}).then(response => {
                console.log(response);
                return response.json()
            });
            result.then(data => {
                console.log(data);
                container.innerHTML = "";
                for(const item of data.data) {
                    const div = document.createElement("div");
                    div.innerText = `${item.id}. ${item.sponsor} ${item.city}`;
                    container.appendChild(div);
                }
            });
        }
    </script>
</body>
</html>