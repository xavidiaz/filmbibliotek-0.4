<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$film = $director = $year = "";
$film_err = $director_err = $year_err = "";

// Categories Query
$query = $mysqli->query("SELECT categories.cat_name, categories.cat_id FROM categories ORDER BY categories.cat_name ASC");

// Processing form data when form is submitted
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Get hidden input value
    $id = $_POST["id"];

    // Validate film
    $input_film = trim($_POST["film"]);
    if (empty($input_film)) {
        $film_err = "Please enter a film.";
    } elseif (!filter_var($input_film, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $film_err = "Please enter a valid film.";
    } else {
        $film = $input_film;
    }


    // Validate director
    $input_director = trim($_POST["director"]);
    if (empty($input_director)) {
        $director_err = "Ange en regissör.";
    } else {
        $director = $input_director;
    }


    // Validate year
    $input_year = trim($_POST["year"]);
    if (empty($input_year)) {
        $year_err = "Ange ett år.";
    } elseif (!ctype_digit($input_year)) {
        $year_err = "Ange ett giltigt datumvärde.";
    } else {
        $year = $input_year;
    }

    // Check input errors before inserting in database
    if (empty($film_err) && empty($director_err) && empty($year_err)) {
        // Prepare an update statement
        $sql = "UPDATE films SET title=?, director=?, cat_id=?, year=? WHERE id=?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ssssi", $param_film, $param_director, $param_category, $param_year, $param_id);

            // Set parameters
            $param_film = $film;
            $param_director = $director;
            $param_category = htmlspecialchars($_POST['category']);
            $param_year = $year;
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else {
                echo "Något gick fel. Vänligen försök igen senare.";
            }
        }

        // Close statement
        $stmt->close();
    }

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter before processing further
    if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
        // Get URL parameter
        $id =  trim($_GET["id"]);

        // Prepare a select statement
        $sql = "SELECT films.id, films.title, films.director, categories.cat_name, films.year FROM films INNER JOIN categories ON films.cat_id = categories.cat_id WHERE id = ?";

        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("i", $param_id);

            // Set parameters
            $param_id = $id;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                $result = $stmt->get_result();

                if ($result->num_rows == 1) {
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    // Retrieve individual field value
                    $film = $row["title"];
                    $director = $row["director"];
                    $category = $row["cat_name"];
                    $year = $row["year"];
                } else {
                    // URL doesn't contain valid id. Redirect to error page
                    header("location: fel.php");
                    exit();
                }
            } else {
                echo "hoppsan! Något gick fel. Vänligen försök igen senare.";
            }
        }

        // Close statement
        $stmt->close();

        // Close connection
        $mysqli->close();
    } else {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: fel.php");
        exit();
    }
}
?>

<!-- HEADER -->
<?include 'templates/header.php' ?>

<!-- HTML -->
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h2>Ändra film</h2>
                </div>

                <form action="<?php echo htmlspecialchars(basename($_SERVER['REQUEST_URI'])); ?>" method="post">

                    <div class="form-group <?php echo (!empty($film_err)) ? 'has-error' : ''; ?>">
                        <label>titel</label>
                        <input type="text" name="film" class="form-control" value="<?php echo $film; ?>" readonly>
                        <span class="help-block"><?php echo $film_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($director_err)) ? 'has-error' : ''; ?>">
                        <label>regissör</label>
                        <input type="text" name="director" class="form-control" value="<?php echo $director; ?>">
                        <span class="help-block"><?php echo $director_err; ?></span>
                    </div>
                    <div class="row">
                        <div class="col my-1 form-group">
                            <label class="mr-sm-2">genre</label><br>
                            <select class="custom-select mr-sm-2" name='category' class='form-control'>
                                <?php
                                while ($rows = $query->fetch_assoc()) {
                                    $category = $rows['cat_name'];
                                    $category_id = $rows['cat_id'];
                                    echo "<option value='$category_id'>$category</option>";
                                }
                                ?>
                            </select>

                        </div>

                        <div class="col  mr-sm-2 form-group <?php echo (!empty($year_err)) ? 'has-error' : ''; ?>">
                            <label class="mr-sm-2">år</label>
                            <input type="number" name="year" id="year" class="form-control" value="<?php echo $year; ?>" min="1950" max="<?php echo date("Y"); ?>">
                            <span class="help-block"><?php echo $year_err; ?></span>
                        </div>
                    </div>

                    <input type="hidden" name="id" value="<?php echo $id; ?>" />
                    <input type="submit" class="btn btn-dark btn-lg" value="Spara">
                    <a href="index.php" class="btn btn-outline-dark btn-lg">Avbryt</a>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<?include 'templates/footer.php' ?>