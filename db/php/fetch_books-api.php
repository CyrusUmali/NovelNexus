<?php
header("Content-Type: application/json");
include '../conn.php';

// Get the search, category, and other filters
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : ''; // Filter for Availability
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'title'; // Default sort column
$direction = isset($_GET['direction']) && strtolower($_GET['direction']) === 'desc' ? 'DESC' : 'ASC'; // Default sort direction

// Define valid columns for sorting
$validColumns = ['title', 'author', 'rating', 'Availability'];
$sortColumn = in_array($sort, $validColumns) ? $sort : 'title';

// Base SQL query
$sql = "
    SELECT 
        books.id, 
        books.title, 
        books.author, 
        books.book_cover, 
        books.Availability,
        GROUP_CONCAT(category.name ORDER BY category.name ASC SEPARATOR ', ') AS category_names,
        AVG(bookReviews.rating) AS average_rating
    FROM books
    JOIN bookCategories ON books.id = bookCategories.book_id
    JOIN category ON bookCategories.category_id = category.id
    LEFT JOIN bookReviews ON books.isbn = bookReviews.book_isbn
    WHERE books.title LIKE '%$search%'";

// Add category filter if provided
if ($category) {
    $sql .= " AND bookCategories.category_id = '$category'";
}

// Add availability filter if provided
if ($filter === 'Available') {
    $sql .= " AND books.Availability = 'Available'";
}

// Add sorting logic
if ($sortColumn === 'rating') {
    $sql .= " GROUP BY books.id ORDER BY average_rating $direction";
} else {
    $sql .= " GROUP BY books.id ORDER BY $sortColumn $direction";
}

$result = $conn->query($sql);

$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            "id" => $row["id"],
            "title" => $row["title"],
            "author" => $row["author"],
            "book_cover" => htmlspecialchars($row["book_cover"]),
            "Availability" => $row["Availability"],
            "category_names" => $row["category_names"],
            "average_rating" => $row["average_rating"] ? floatval($row["average_rating"]) : null // Set to null if no reviews
        ];
    }
}

echo json_encode($books);
$conn->close();
?>
