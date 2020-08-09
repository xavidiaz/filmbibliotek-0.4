<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$film = $director = $category =  $year = "";
$film_err = $director_err =  $category_err = $year_err = "";

// Categories Query
$query = $mysqli->query("SELECT categories.cat_name, categories.cat_id FROM categories ORDER BY categories.cat_name ASC");

// Processing form data when form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate title
    $input_film = trim($_POST["film"]);
    if (empty($input_film)) {
        $film_err = "Ange en film.";
    } elseif (!filter_var($input_film, FILTER_VALIDATE_REGEXP, array("options" => array("regexp" => "/^[a-zA-Z\s]+$/")))) {
        $film_err = "Ange en giltig namnfilm.";
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
        $year_err = "Vänligen ange året.";
    } elseif (!ctype_digit($input_year)) {
        $year_err = "Vänligen ange ett giltigt år.";
    } else {
        $year = $input_year;
    }



    // Check input errors before inserting in database
    if (empty($film_err) && empty($director_err) && empty($year_err)) {



        // Prepare an insert statement
        $sql = "INSERT INTO films (title, director, cat_id, year) VALUES (?, ?, ?, ?)";


        if ($stmt = $mysqli->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("sssi", $param_film, $param_director, $param_category, $param_year);

            // Set parameters
            $param_film = $film;
            $param_director = $director;
            $param_category = htmlspecialchars($_POST['category']);
            $param_year = $year;

            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Records created successfully. Redirect to landing page
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
}
?>

<!-- HEADER -->
<?include 'templates/header.php' ?>

<!-- HTML -->
<div class="wrapper">
    <div class="container">
        <div class="row mt-5">
            <div class="col-md-9 mx-auto">
                <div class="page-header mb-4">
                    <h2>Lägga en film</h2>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($film_err)) ? 'has-error' : ''; ?>">
                        <label>film</label>
                        <input type="text" name="film" class="form-control" value="<?php echo $film; ?>">
                        <span class="help-block"><?php echo $film_err; ?></span>
                    </div>

                    <div class="form-group <?php echo (!empty($director_err)) ? 'has-error' : ''; ?>">
                        <label>Regissör</label>
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
                    <div class="row mt-5">

                        <input type="submit" class="col btn btn-dark btn-lg btn-lg mx-2" value="Spara">
                        <a href="index.php" class="col btn btn-outline-dark btn-lg mx-2">Avbryt</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<!-- FOOTER -->
<?php include 'templates/footer.php' ?>