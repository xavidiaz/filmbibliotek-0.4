<!-- HEADER -->
<?include 'templates/header.php' ?>

<!-- HTML -->
<div class="container">

    <div class="row mt-3">
        <div class="col-12">
            <div class="page-header clearfix">
                <h2 class="pull-left pl-3 pb-2">Films</h2>

            </div>
            <?php
            // Include config file
            require_once "config.php";

            // Attempt select query execution
            $sql = "SELECT films.id, films.title, films.director, categories.cat_name, films.year FROM films INNER JOIN categories ON films.cat_id = categories.cat_id";
            if ($result = $mysqli->query($sql)) {
                if ($result->num_rows > 0) {

                    echo "<table class='table  table-striped table-hover'>";
                    echo "<thead class='thead-dark'>";
                    echo "<tr>";

                    echo "<th>titel</th>";
                    echo "<th>regissör</th>";
                    echo "<th>genre</th>";
                    echo "<th>år</th>";
                    echo "<th scope='row'>Handling</th>";
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
                        echo "<a href='film.php?id=" . $row['id'] . "' title='Film Detaljer' data-toggle='tooltip'><i class='material-icons-outlined text-secondary px-1'>remove_red_eye</i></a>";
                        echo "<a href='andra.php?id=" . $row['id'] . "' title='Ändra Film' data-toggle='tooltip'><i class='material-icons-outlined text-secondary px-1'>edit</i></span></a>";
                        echo "<a href='radera.php?id=" . $row['id'] . "' title='Radera Film' data-toggle='tooltip'><i class='material-icons-outlined text-secondary px-1'>delete_forever</i></a>";
                        echo "</td>";
                        echo "</tr>";
                    }
                    echo "</tbody>";
                    echo "</table>";

                    // Free result set
                    $result->free();
                } else {
                    echo "<p class='lead'><em>Inga filmer hittades.</em></p>";
                }
            } else {
                echo "FEL: Det gick inte att köra $sql. " . $mysqli->error;
            }

            // Close connection
            $mysqli->close();
            ?>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?include 'templates/footer.php' ?>