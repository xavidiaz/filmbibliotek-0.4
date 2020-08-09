<?php
// Check existence of id parameter before processing further
if (isset($_GET["id"]) && !empty(trim($_GET["id"]))) {
    // Include config file
    require_once "config.php";

    // Prepare a select statement

    $sql = "SELECT films.id, films.title, films.director, categories.cat_name, films.year FROM films INNER JOIN categories ON films.cat_id = categories.cat_id  WHERE id = ?";
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_GET["id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows == 1) {
                /* Fetch result row as an associative array. Since the result set
                contains only one row, we don't need to use while loop */
                $row = $result->fetch_array(MYSQLI_ASSOC);

                // Retrieve individual field value
                $id = $row['id'];
                $title = $row['title'];
                $director = $row["director"];
                $category = $row['cat_name'];
                $year = $row['year'];
            } else {
                // URL doesn't contain valid id parameter. Redirect to error page
                header("location: fel.php");
                exit();
            }
        } else {
            echo "Oops! Something went wrong. Please try again later.";
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
?>
<!-- HEADER -->
<?include 'templates/header.php' ?>

<!-- HTML -->

<div class="container">
    <div class="row align-items-centeralign-items-center mt-5">
        <div class="col-md-6 mx-auto">
            <div class="card text-center">

                <div class="card-body">
                    <h5 class="card-title"><?php echo $row["title"]; ?></h5>
                    <p class="card-text"><?php echo $row['director'] ?></p>
                </div>
                <div class="row card-footer text-muted mx-1 my-1">
                    <p class="col card-text"><?php echo $row['cat_name']; ?></p>
                    <p class="col form-control-static"><?php echo $row["year"]; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- FOOTER -->
<?include 'templates/footer.php' ?>