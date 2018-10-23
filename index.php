<?php

include 'config.php';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT * FROM `play_list` ORDER BY `play_list`.`id` DESC LIMIT 10";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
        echo "<div>";
    while($row = $result->fetch_assoc()) {
        
        echo '<div style="clear: both">';

        echo $row["artist"].' - '.$row["title"];
        
        ?>
            <form action="rating.php" method="post" style="float: right">
                <input type="hidden" name="item_id" value="<?php echo $row["id"]; ?>">
                <button type="submit">up</button>
            </form>
        <?php

        echo ' <span style="float: right">- '.$row["rating"].' -</span>';
        echo "<hr>";
        echo "</div>";
        
    }
}

$conn->close();
