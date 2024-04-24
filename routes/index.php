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
    <h2></h2>
    <div id="background" style="width: 300px; height: 300px; border: 1px solid white"></div>
    <input id="id" type="number" onchange="getItem()"/>
    <button onclick="getItem()">Pobierz dane</button><hr/>
    <input type="text" id="name" placeholder="name">
    <input type="text" id="shortcut" placeholder="shortcut">
    <input type="text" id="colors" placeholder="colors" />
    <button onclick="setColors()">Ustaw kolor</button>
    <script>
        async function getItem() {
            const id = document.getElementById("id").value;
            const team = document.getElementsByTagName('h2')[0];
            const background = document.getElementById('background');
            fetch(`http://localhost/phpork/routes/api/country.php?id=${id}`, {method: "GET"}).then(response => {
                return response.json()
            }).then(data => {
                console.log(data);
                team.innerText = `${data.data.id}. ${data.data.name} (${data.data.shortcut})`;
                background.style.background = `linear-gradient(90deg, ${data.data.colors.join(',')})`;
            });
        }

        async function setColors() {
            const id = document.getElementById("id").value;
            const colors = document.getElementById('colors').value;
            const name = document.getElementById('name').value;
            const shortcut = document.getElementById('shortcut').value
            fetch(`http://localhost/phpork/routes/api/country.php`, {method: "POST", body: JSON.stringify({data: {"name":`"${name}"`, "shortcut":`"${shortcut}"`, "colors":`"${colors}"`}})}).then(response => {
                console.log(response);
                return response.json()
            }).then(data => {
                getItem();
            });
        }

        async function createTeam() {
            const name = document.getElementById('name')
        }
    </script>
</body>
</html>