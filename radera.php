<?php
// Process delete operation after confirmation
if (isset($_POST["id"]) && !empty($_POST["id"])) {

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
<?php include('templates/header.php') ?>

<!-- BODY -->
<div class="wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="page-header">
                    <h1>Delete Film</h1>
                </div>
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="alert alert-danger fade in">
                        <input type="hidden" name="id" value="<?php echo trim($_GET["id"]); ?>" />
                        <p>Är du säker på att du vill ta bort den här filmen?</p><br>
                        <p>
                            <input type="submit" value="Ja" class="btn btn-danger">
                            <a href="index.php" class="btn btn-default">Nej</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</body>

</html>