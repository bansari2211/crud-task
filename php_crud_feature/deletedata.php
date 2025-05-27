<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

$sql = "SELECT documents FROM students WHERE id = $id";
$result = $conn->query($sql);

    if ($result && $result->num_rows>0)
    {
        $row = $result->fetch_assoc();
        $images=json_decode($row['documents']);
        if($images){
            foreach($images as $img) {
                $img = $img;
                $filepath = 'media/' . $img;
                if (file_exists($filepath)) {
                    unlink($filepath);
                }
            }
        }
        $deleteqry = "DELETE FROM students WHERE id = $id";
        if ($conn->query($deleteqry)) {
            echo "<script>
                    alert('Record Deleted successfully!');
                    window.location.href = 'index.php';
                    </script>";
        }
        else {
            echo "<script>
                    alert('Record Not Deleted successfully!');
                    window.location.href = 'RegistartionForm.php';
                    </script>";
        }
    }
}
?>



?>