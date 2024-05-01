<?php

use App\Collections\Collection;
use App\Models\Country;
use App\Models\Schedule;
use App\Models\Team;

require_once "./../boot.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cope</title>
</head>
<body>
    <input type="number" id="language" />
    <button onclick="update()">Wyświetl</button>
    <div></div>
    <script>
        function update() {
            const input = document.getElementById('language').value;
            const sentence = fetch(`http://localhost/phpork/routes/api/team.php`, {method: "DELETE", body: JSON.stringify({id: input})}).then(response => {return response.json()});
            console.log(sentence.then( data => {
                document.getElementsByTagName('div')[0].innerHTML = data.data;
                return data.data}
            ));
        }
    </script>
</body>
</html>