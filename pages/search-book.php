<div class="books" id="booksPage">
    <div class="upper-block">
        <h3 onclick="fetchBooks()">Books</h3>

        <div class="section">

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

    <div class="pnotif-card" id="pnotif-card">


        <div class="pnotif-icon-container">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"
                stroke-width="0"
                fill="currentColor"
                stroke="currentColor"
                class="pnotif-icon">
                <path
                    d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"></path>
            </svg>
        </div>
        <div class="pnotif-message-text-container">
            <p class="pnotif-message-text">Added to Shelf</p>
        </div>
        <svg onclick="removeToast()"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 15 15"
            stroke-width="0"
            fill="none"
            stroke="currentColor"
            class="pnotif-cross-icon">
            <path
                fill="currentColor"
                d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"
                clip-rule="evenodd"
                fill-rule="evenodd"></path>
        </svg>
    </div>



    <div class="pnotif-card" id="pnotif-card2">


        <div class="pnotif-icon-container">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 512 512"
                stroke-width="0"
                fill="currentColor"
                stroke="currentColor"
                class="pnotif-icon">
                <path
                    d="M256 48a208 208 0 1 1 0 416 208 208 0 1 1 0-416zm0 464A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209c9.4-9.4 9.4-24.6 0-33.9s-24.6-9.4-33.9 0l-111 111-47-47c-9.4-9.4-24.6-9.4-33.9 0s-9.4 24.6 0 33.9l64 64c9.4 9.4 24.6 9.4 33.9 0L369 209z"></path>
            </svg>
        </div>
        <div class="pnotif-message-text-container">
            <p class="pnotif-message-text">Removed from Shelf</p>
        </div>
        <svg onclick="removeToast()"
            xmlns="http://www.w3.org/2000/svg"
            viewBox="0 0 15 15"
            stroke-width="0"
            fill="none"
            stroke="currentColor"
            class="pnotif-cross-icon">
            <path
                fill="currentColor"
                d="M11.7816 4.03157C12.0062 3.80702 12.0062 3.44295 11.7816 3.2184C11.5571 2.99385 11.193 2.99385 10.9685 3.2184L7.50005 6.68682L4.03164 3.2184C3.80708 2.99385 3.44301 2.99385 3.21846 3.2184C2.99391 3.44295 2.99391 3.80702 3.21846 4.03157L6.68688 7.49999L3.21846 10.9684C2.99391 11.193 2.99391 11.557 3.21846 11.7816C3.44301 12.0061 3.80708 12.0061 4.03164 11.7816L7.50005 8.31316L10.9685 11.7816C11.193 12.0061 11.5571 12.0061 11.7816 11.7816C12.0062 11.557 12.0062 11.193 11.7816 10.9684L8.31322 7.49999L11.7816 4.03157Z"
                clip-rule="evenodd"
                fill-rule="evenodd"></path>
        </svg>
    </div>

</div>