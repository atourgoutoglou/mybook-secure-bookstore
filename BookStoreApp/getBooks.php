<?php
header("Content-Type: application/json; charset=UTF-8");
include 'config.php';

$conn->set_charset("utf8");

$sql = "SELECT 
            id,
            title, 
            image_path AS img, 
            author, 
            publisher, 
            pages, 
            price, 
            stars AS rating, 
            description AS summary 
        FROM books";

$result = $conn->query($sql);
$books = [];

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $row['title'] = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
        $row['author'] = htmlspecialchars($row['author'], ENT_QUOTES, 'UTF-8');
        $row['publisher'] = htmlspecialchars($row['publisher'], ENT_QUOTES, 'UTF-8');
        $row['summary'] = htmlspecialchars($row['summary'], ENT_QUOTES, 'UTF-8');
        $row['price'] = (float)$row['price'];
        $row['rating'] = (float)$row['rating'];
        $books[] = $row;
    }   
}

echo json_encode($books, JSON_UNESCAPED_UNICODE);

$conn->close();
?>
