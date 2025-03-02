<?php include '../db/session.php'; ?>

<form class="addNewBook-container" id="addNewBook">
    <div class="head">
        <div>
            <i class='bx bx-book-add'></i>
            <span>Add New Book</span>
        </div>

        <i class='bx bx-x' onclick="closePopup()"></i>
    </div>

    <div class="form-body">
        <div class="col-1">
            <div class="row-2">
                <div class="item">
                    <label for="booktitle">Book Title</label>
                    <input type="text" placeholder="Enter the Book's Title" id="booktitle">
                </div>

                <div class="item">
                    <label for="bookauthor">Author</label>
                    <input type="text" placeholder="Enter the Book's Author" id="bookauthor">
                </div>
            </div>

            <div class="row-2">
                <div class="item">
                    <label for="bookisbn">ISBN</label>
                    <input type="text" placeholder="Enter the Book's ISBN" id="bookisbn">
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
                    <!-- Selected categories will be displayed here -->
                </div>
            </div>

            <div class="row-1">
                <div id="editor"></div>
            </div>
        </div>

        <div class="col-2">
            <div class="row-1">
                <!-- Image preview -->
                <img id="imagePreview">

                <label for="bookcover">Book Cover</label>
                <input type="file" id="bookcover" accept="image/*" onchange="displayImage(event)">
            </div>

            <div class="row-1">

                <label for="bookcover">Access Level</label>
                <select id="accessLvl">
                    <option value="0" selected >Free</option>
                    <option value="1">Paid</option>

                </select>
            </div>
        </div>
    </div>

    <button type="button" id="addBookButton" onclick="addNewBookClick()">Add New Item</button>
</form>