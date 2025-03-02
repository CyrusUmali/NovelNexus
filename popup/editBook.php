<?php
// Include database connection
include '../db/conn.php';

// Check if 'id' is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookId = $_GET['id'];

    // Prepare the SQL query to retrieve the book data based on the provided id
    $sql = "SELECT * FROM books WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookId);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if a book was found
    if ($result->num_rows > 0) {
        // Fetch the book data
        $book = $result->fetch_assoc();

        // Retrieve the current access level of the book
        $currentAccessLevel = $book['accesslvl'];
    } else {
        echo "Book not found.";
        exit();
    }

    // Query to get the selected categories for the book
    $sqlCategories = "
        SELECT c.id, c.name
        FROM category c
        JOIN bookCategories bc ON bc.category_id = c.id
        WHERE bc.book_id = ?
    ";

    // Prepare and execute the query to fetch categories
    $stmtCategories = $conn->prepare($sqlCategories);
    $stmtCategories->bind_param("i", $bookId);
    $stmtCategories->execute();
    $resultCategories = $stmtCategories->get_result();

    // Fetch categories for the book
    $categories = [];
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row;
    }

    // Close the statement for categories
    $stmtCategories->close();

    // Close the statement for the book
    $stmt->close();
} else {
    echo "No valid ID provided.";
    exit();
}
?>
<!-- HTML Form with Pre-filled Data for Editing -->
<form class="addNewBook-container" id="addNewBook">
    <div class="head">
        <div>
            <i class='bx bx-edit-alt'> </i>
            <span>Edit Book</span>
        </div>

        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body">
        <div class="col-1">

            <div class="row-2">

                <div class="item">
                    <label for="booktitle">Book Title</label>
                    <input type="text" placeholder="Enter the Book's Title" id="booktitle" value="<?php echo htmlspecialchars($book['title']); ?>">

                </div>


                <div class="item">
                    <label for="bookauthor">Author</label>
                    <input type="text" placeholder="Enter the Book's Author" id="bookauthor" value="<?php echo htmlspecialchars($book['author']); ?>">
                </div>
            </div>


            <div class="row-2">


                <div class="item">
                    <label for="bookisbn">ISBN</label>
                    <input type="text" placeholder="Enter the Book's ISBN" id="bookisbn" value="<?php echo htmlspecialchars($book['isbn']); ?>">
                </div>

                <div class="item">
                    <label for="bookcategory">Category</label>
                    <select id="bookcategory" onchange="handleSelectedCategs()">
                        <option value="" selected disabled>Select a category</option>
                        <?php
                        if ($conn) {
                            $sql = "SELECT * FROM category";
                            $query = $conn->query($sql);
                            if ($query) {
                                while ($crow = $query->fetch_assoc()) {
                                    echo "<option value='" . htmlspecialchars($crow['id']) . "'>" . htmlspecialchars($crow['name']) . "</option>";
                                }
                            } else {
                                echo "<option value='' disabled>Error retrieving categories. Please try again later.</option>";
                            }
                            $conn->close();
                        } else {
                            echo "<option value='' disabled>Error connecting to the database. Please try again later.</option>";
                        }
                        ?>
                    </select>

                </div>






            </div>

            <div class="row-1">
                <label>Selected Categories:</label>
                <div id="selectedCategories" class="selected-categories-container">
                    <?php

                    // Loop through the categories and generate the HTML for each one
                    foreach ($categories as $category) {
                        echo '<div class="selected-category-item" data-id="' . $category['id'] . '">';
                        echo '<span>' . htmlspecialchars($category['name']) . '</span>';
                        echo '<i class="bx bx-x nav__icon" style="cursor: pointer;"></i>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>



            <div class="row-1">
                <div id="editBookeditor">

                </div>

                <input type="hidden" id="aboutBook" value="<?php echo htmlspecialchars($book['about']); ?>">

            </div>




        </div>

        <div class="col-2">

            <div class="row-1">


                <!-- Display current cover image if available -->
                <?php if (!empty($book['book_cover'])): ?>
                    <img id="imagePreview" src="<?php echo htmlspecialchars($book['book_cover']); ?>" alt="Current Book Cover">
                <?php else: ?>
                    <img id="imagePreview" src="" alt="No cover image">
                <?php endif; ?>

                <label for="bookcover">Book Cover</label>
                <input type="file" id="bookcover" accept="image/*" onchange="displayImage(event)">
            </div>

            <div class="row-1">

                <label for="bookcover">Access Level</label>
                <select id="accessLvl" name="access_level">
                    <option value="0" <?= $currentAccessLevel == 0 ? 'selected' : '' ?>>Free</option>
                    <option value="1" <?= $currentAccessLevel == 1 ? 'selected' : '' ?>>Paid</option>
                </select>

            </div>



        </div>
    </div>

    <button type="button" onclick="updateBookClick(<?php echo $bookId; ?>)">Update Book</button>
</form>