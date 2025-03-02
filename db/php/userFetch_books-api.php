<?php
header("Content-Type: application/json");
include '../conn.php';
session_start();

// Check if user info exists in the session
if (!isset($_SESSION['userInfo'][0])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not logged in'
    ]);
    exit();
}

$userInfo = $_SESSION['userInfo'][0];
$user_id = $userInfo['id'];

$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : '';
$category = isset($_GET['category']) ? $conn->real_escape_string($_GET['category']) : '';
$sort = isset($_GET['sort']) ? $conn->real_escape_string($_GET['sort']) : 'title'; // Default sort column
$direction = isset($_GET['direction']) && strtolower($_GET['direction']) === 'desc' ? 'DESC' : 'ASC'; // Default sort direction
$filter = isset($_GET['filter']) ? $conn->real_escape_string($_GET['filter']) : ''; // Filter availability

// Define valid columns for sorting
$validColumns = ['title', 'author', 'rating', 'Availability'];
$sortColumn = in_array($sort, $validColumns) ? $sort : 'title';

// Base SQL query
$sql = "
    SELECT 
        books.isbn, 
        books.id, 
        books.title, 
        books.author, 
        books.book_cover, 
        books.Availability, 
        GROUP_CONCAT(DISTINCT category.name ORDER BY category.name ASC SEPARATOR ', ') AS category_names,
        CASE WHEN shelf.id IS NOT NULL THEN 1 ELSE 0 END AS in_shelf,
        AVG(bookReviews.rating) AS average_rating
    FROM books
    JOIN bookCategories ON books.id = bookCategories.book_id
    JOIN category ON bookCategories.category_id = category.id
    LEFT JOIN shelf ON books.id = shelf.book_id AND shelf.user_id = ?
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

// Prepare and execute the query
$statement = $conn->prepare($sql);
$statement->bind_param("i", $user_id);
$statement->execute();
$result = $statement->get_result();

// Fetch and process results
$books = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $books[] = [
            "id" => $row["id"],
            "title" => $row["title"],
            "author" => $row["author"],
            "book_cover" => htmlspecialchars($row["book_cover"]),
            "Availability" => $row["Availability"],
            "category_names" => $row["category_names"], // Concatenated category names
            "in_shelf" => $row["in_shelf"], // 1 if in shelf, 0 otherwise
            "average_rating" => $row["average_rating"] ? floatval($row["average_rating"]) : null // Set to null if no reviews
        ];
    }
}

// Return results as JSON
echo json_encode($books);

// Close resources
$statement->close();
$conn->close();
?>
