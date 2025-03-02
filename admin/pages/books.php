<div class="books" id="booksPage">
    <div class="upper-block">
        <h3 onclick="testClick()">Book Management</h3>

        <div class="section">
            <button onclick="addBookClick()">
                <i class='bx bx-plus-circle'></i>
                Add Book
            </button>
            <div class="search">
                <i class='bx bx-search'></i>
                <input type="text" placeholder="Search by Title" oninput="fetchBooks(this.value)">
            </div>
        </div>

    </div>

    <div class="category-wrapper">



        <select id="bookcategory" onchange="fetchBooks()">
            <option value="" selected>Category</option>
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
                // Close the database connection
                $conn->close();
            } else {
                echo "<option value='' disabled>Error connecting to the database. Please try again later.</option>";
            }
            ?>
        </select>



    </div>

    <div class="search-results">
        <div class="result-wrapper">
            <table class="books-table">
                <thead>
                    <tr>
                        <td>
                            <div>
                                Book Title
                                <i class="bx bx-sort navActive" onclick="toggleSort('title')"></i>
                            </div>
                        </td>
                        <td>
                            <div>
                                Author
                                <i class="bx bx-sort" onclick="toggleSort('author')"></i>
                            </div>
                        </td>
                        <td>
                            <div>
                                Ratings
                                <i class="bx bx-sort" onclick="toggleSort('rating')"></i>
                            </div>
                        </td>
                        <td>
                            <div>Category</div>
                        </td>
                        <td>
                            <div class="availability-filter">
                                Availability
                                <i class="bx bx-filter-alt" onclick="toggleFilter()"></i>
                            </div>
                        </td>
                        <td></td>
                    </tr>
                </thead>
                <tbody id="booksTableBody">
                    <!-- JavaScript will populate this section -->
                </tbody>
            </table>
        </div>
    </div>
</div>