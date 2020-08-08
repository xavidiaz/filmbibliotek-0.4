<!-- HEADER -->
<?php include('templates/header.php') ?>

<!-- BODY -->

<div class="container">
    <div class="row">
        <div class="col s12 m12 l12">

            echo "<th>titel</th>";
            echo "<th>regissör</th>";
            echo "<th>genre</th>";
            echo "<th>åf</th>";
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
                        echo "<a href='film.php?id=" . $row[' id'] . "' title='View Film' data-toggle='tooltip'><span class='glyphicon glyphicon-eye-open'></span></a>" ; echo "<a href='andra.php?id=" . $row['id'] . "' title='Update Film' data-toggle='tooltip'><span class='glyphicon glyphicon-pencil'></span></a>" ; echo "<a href='radera.php?id=" . $row['id'] . "' title='Delete Film' data-toggle='tooltip'><span class='glyphicon glyphicon-trash'></span></a>" ; echo "</td>" ; echo "</tr>" ; } echo "</tbody>" ; echo "</table>" ; // Free result set $result->free();
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
    </body>

    </html>