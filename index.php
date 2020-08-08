<!-- HEADER -->
<?php include('templates/header.php') ?>

<!-- BODY -->

<div class="container">
    <div class="row">
        <div class="col s12 m12 l12">

            <h2>Films</h2>
            <a href="skapa.php" class="btn orange darken-2">Lägg till ny film</a>
        </div>
        <?php

        // Attempt select query execution
        $sql = "SELECT films.id, films.title, films.director, categories.cat_name, films.year FROM films INNER JOIN categories ON films.cat_id = categories.cat_id";
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows > 0) {
                echo "<table class='responsive-table striped'>";
                echo "<thead>";
                echo "<tr>";

                echo "<th>titel</th>";
                echo "<th>regissör</th>";
                echo "<th>genre</th>";
                echo "<th>år</th>";
                echo "<th>Handling</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                while ($row = $result->fetch_array()) {
                    echo "<tr>";

                    echo "<td>" . $row['title'] . "</td>";
                    echo "<td>" . $row['director'] . "</td>";
                    echo "<td>" . $row['cat_name'] . "</td>";
                    echo "<td>" . $row['year'] . "</td>";
                    echo "<td>";
                    echo "<a href='film.php?id=" . $row['id'] . "'><i data-tooltip='Film detaljer' class='small material-icons orange-text text-darken-2'>remove_red_eye</i></a>";
                    echo "<a href='andra.php?id=" . $row['id'] . "'><i  data-tooltip='Ändra Film' class='small material-icons orange-text text-darken-2'>mode_edit</i></a>";
                    echo "<a href='radera.php?id=" . $row['id'] . "' ><i data-tooltip='Radera Film' class='small material-icons orange-text text-darken-2 tooltipped''>delete_forever</i></a>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                // Free result set
                $result->free();
            } else {
                echo "<p class='flow-text'><em>Inga filmer hittades.</em></p>";
            }
        } else {
            echo "FEL: Det gick inte att köra $sql. " . $mysqli->error;
        }

        // Close connection
        $mysqli->close();
        ?>

    </div>
</div>


<!-- FOOTER -->
<?php include('templates/footer.php') ?>