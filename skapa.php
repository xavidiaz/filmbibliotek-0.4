<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$film = $director =  $year = "";
$film_err = $director_err =  $year_err = "";

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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Lägg till en film</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <script src="/path/to/cdn/jquery.slim.min.js"></script>

    <!--TO BE USED WITH TWITTER BOOTSTRAP-->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.min.js"></script>
    <link rel="stylesheet" href="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.1.1/css/bootstrap.min.css" />
    <script src="http://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/2.1.1/bootstrap.min.js"></script>
    <!-- end bootstrap and jquery plugins --->

    <!-- Datepicker css and jquery -->
    <link rel="stylesheet" type="text/css" href="css/datepicker.css" />
    <script src="bootstrap-fulldatepicker.js"></script>
    <script>
        $(document).on("focus", ".yearpicker", function(e) {
            $(this).datepicker({
                format: "yyyy",
                startDate: "01.01.2012",
                viewMode: "years",
                minViewMode: "years",
            });
        });
    </script>

    <style type="text/css">
        .wrapper {
            width: 500px;
            margin: 0 auto;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-header">
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

                        <div class="form-group">
                            <label>genre</label><br>
                            <select name="category">
                                <?php
                                while ($rows = $query->fetch_assoc()) {
                                    $category = $rows['cat_name'];
                                    $category_id = $rows['cat_id'];
                                    echo "<option value='$category_id'>$category</option>";
                                }
                                ?>
                            </select>
                            <span class="help-block"></span>
                        </div>

                        <div class="form-group <?php echo (!empty($year_err)) ? 'has-error' : ''; ?>">
                            <label>år</label>
                            <input type="number" name="year" id="year" class="form-control" value="<?php echo $year; ?>" min="1950" max="<?php echo date("Y"); ?>">
                            <span class="help-block"><?php echo $year_err; ?></span>
                        </div>


                        <input type="submit" class="btn btn-primary" value="Skicka">
                        <a href="index.php" class="btn btn-default">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>