<?php

// Process delete operation after confirmation
if (isset($_POST["id"]) && !empty($_POST["id"])) {
    // Include config file
    require_once "config.php";

    // Prepare a delete statement
    $sql = "DELETE FROM films WHERE id = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("i", $param_id);

        // Set parameters
        $param_id = trim($_POST["id"]);

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            // Records deleted successfully. Redirect to landing page
            header("location: index.php");
            exit();
        } else {
            echo "hoppsan! Något gick fel. Vänligen försök igen senare.";
        }
    }

    // Close statement
    $stmt->close();

    // Close connection
    $mysqli->close();
} else {
    // Check existence of id parameter
    if (empty(trim($_GET["id"]))) {
        // URL doesn't contain id parameter. Redirect to error page
        header("location: radera.php");
        exit();
    }
}
?>
<!-- HEADER -->
<?include 'templates/header.php' ?>

<!-- HTML -->

<div class="container ">
    <div class="row py-3">
        <div class="col col-8 mx-auto">
            <div class="pull-left pl-3 pb-2">
                <h1>Delete Film</h1>
            </div>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="alert alert-danger px-auto py-4 text-center">
                    <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                    <h4>Är du säker på att du vill ta bort den här filmen?</h4><br>
                    <p>
                        <input type="submit" value="Ja" class="btn btn-danger btn-lg col-4">
                        <a href="index.php" class="btn btn-outline-dark btn-lg col-4">Nej</a>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- FOOTER -->
<?include 'templates/footer.php' ?>