 <div class="books " id="usersPage">

     <div class="upper-block">

         <h3>User Management</h3>



         <div class="section">

             <div class="search">
                 <i class='bx bx-search'></i>
                 <input type="text" 
                 oninput="userSearch(event)"
                 placeholder="Search by Name " id="userSearch">
             </div>
         </div>



     </div>


     <div class="users-section" style="background-color: unset;">

         <?php
            // Fetch available subscription plans
            $plansResult = $conn->query("SELECT id, name FROM plans");

            // Fetch user data for the current page (already implemented in your code)
            $page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? (int)$_GET['page'] : 1;
            $limit = 8;
            $offset = max(($page - 1) * $limit, 0); // Ensures offset is never negative

            // Fetch only the rows for the current page
            $sql = "SELECT id, fname, lname, membership, photo, status, phone, email, 
            plan_id FROM 
            users LIMIT $limit OFFSET $offset";
            $result = $conn->query($sql);

            // Calculate total pages
            $defaultUser = $result->fetch_assoc();
            $totalUsers = $conn->query("SELECT COUNT(id) AS total FROM users")->fetch_assoc()['total'];
            $totalPages = ceil($totalUsers / $limit);
            ?>




         <div class="row2" style="max-height:400px; box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;  ">
             <h3>Members List</h3>
             <table class="overdueTbl">
                 <thead>
                     <tr>
                         <td>User ID</td>
                         <td>User Name</td>
                         <td>Book Issued</td>
                         <td>Plan</td>
                     </tr>
                 </thead>
                 <tbody>
                     <!-- Data will be inserted here via AJAX -->
                 </tbody>
             </table>


             <div class="bottom" id="usersTablePaginationContainer" >
                 <ul class="page-ctrl" id="pagination">
                     
                     <!-- Dynamic page numbers will be inserted here -->
                   
                 </ul>
             </div>



         </div>

         <?php
            // $conn->close();
            ?>


         <div class="user-profile">

             <div class="head">

                 <div class="left">

                     <div class="left-1">
                         <img id="profileImage" src="photo/<?php echo $defaultUser['id']; ?>" alt="">
                     </div>


                     <div class="left-2">
                         <b id="userName"><?php echo $defaultUser['fname'] . ' ' . $defaultUser['lname']; ?></b><br>

                         <!-- <label for=""><?php echo $defaultUser['photo']; ?></label> -->
                     </div>



                 </div>

                 <!-- <button>Update</button> -->

             </div>

             <form action="" class="user-profile-form">
                 <b>Change User Information Here <i class='bx bx-trash'></i></b>
                 <div class="row">
                     <div>
                         <label for="fname">First Name</label>
                         <input  class="readonlyInp"  readonly type="text" id="fname" value="<?php echo $defaultUser['fname']; ?>">
                     </div>
                     <div>
                         <label for="lname">Last Name</label>
                         <input class="readonlyInp"    readonly type="text" id="lname" value="<?php echo $defaultUser['lname']; ?>">
                     </div>


                 </div>

                 <div class="row">
                     <div>
                         <label for="phone">Phone Number</label>
                         <input class="readonlyInp"  readonly type="text" id="phone" value="<?php echo $defaultUser['phone']; ?>">
                     </div>

                     <div>
                         <label for="email">Email</label>
                         <input  class="readonlyInp" readonly type="text" id="email" value="<?php echo $defaultUser['email']; ?>">
                     </div>

                 </div>
                 <div class="row">

                     <div>
                         <label for="plan">Subscription Plan</label>
                         <select id="plan">
                             <?php while ($plan = $plansResult->fetch_assoc()): ?>
                                 <option value="<?php echo $plan['id']; ?>"
                                     <?php echo ($defaultUser['plan_id'] == $plan['id']) ? 'selected' : ''; ?>>
                                     <?php echo $plan['name']; ?>
                                 </option>
                             <?php endwhile; ?>
                         </select>
                     </div>

                     <div>
                         <label for="status">Status</label>
                         <select id="status">
                             <option value="Active" <?php echo ($defaultUser['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                             <option value="Restricted" <?php echo ($defaultUser['status'] == 'Restricted') ? 'selected' : ''; ?>>Restricted</option>
                             <option value="Banned" <?php echo ($defaultUser['status'] == 'Banned') ? 'selected' : ''; ?>>Banned</option>
                         </select>
                     </div>
                 </div>



                 <div class="row">

                     <div>
                         <label for="">From</label>
                         <input type="date" name="loan_from" id="plan_start">
                     </div>

                     <div>
                         <label for="">To</label>
                         <input type="date" name="loan_to" id="plan_end">
                     </div>
                 </div>




                 <button   type="button" id="updateButton">Update Information</button>
             </form>

             <div class="row2" style="width: 100%;">


                 <h3>Borrowed History</h3>

                 <table class="overdueTbl">

                     <thead>
                         <tr>
                             <td>Loan ID</td>
                             <td>Book Title</td>
                             <td>Start Date</td>
                             <td>Return Date</td>
                             <td>Status</td>
                         </tr>
                     </thead>

                     <tbody id="userLoanHistory">
                         <?php
                            // Assuming you have a database connection $conn already established
                            // Query to fetch overdue book loans with user and book details
                            $query = "
                            SELECT u.id AS user_id, CONCAT(u.fname, ' ', u.lname) AS member, b.id AS book_id , 
                                    b.title AS book_title, b.author,b.isbn , l.loan_from, l.loan_to, l.status, l.fine 
                            FROM loans l
                            JOIN users u ON l.user_id = u.id
                            JOIN books b ON l.book_id = b.id
                
                                ORDER BY l.loan_to DESC
                            ";

                            // Execute the query
                            $result = $conn->query($query);

                            // Check if there are rows returned
                            if ($result->num_rows > 0) :
                                while ($row = $result->fetch_assoc()) :
                            ?>
                                 <tr>
                                     <td><?php echo htmlspecialchars($row['isbn']); ?></td>
                                     <td><span><?php echo htmlspecialchars($row['book_title']); ?></span></td>
                                     <td><?php echo htmlspecialchars(date("F j, Y", strtotime($row['loan_to']))); ?></td>
                                     <td><?php echo htmlspecialchars(date("F j, Y", strtotime($row['loan_from']))); ?></td>
                                     <td><?php echo htmlspecialchars($row['status']); ?></td>
                                 </tr>
                             <?php
                                endwhile;
                            else :
                                ?>
                             <tr>
                                 <td colspan="9">No books Loaned</td>
                             </tr>
                         <?php
                            endif;
                            ?>
                     </tbody>


                 </table>

                 <!-- <div class="bottom">
                     <ul class="page-ctrl">
                         <a href="#">
                             <li> <i class='bx bx-chevron-left'></i></li>
                         </a>
                         <a class="is-active" href="#">
                             <li>1</li>
                         </a>
                         <a href="#">
                             <li>2</li>
                         </a>
                         <a href="#">
                             <li>3</li>
                         </a>
                         <a href="#">
                             <li>4</li>
                         </a>
                         <a href="#">
                             <li>5</li>
                         </a>

                         <a href="#">
                             <li> <i class='bx bx-chevron-right'></i></li>
                         </a>
                     </ul>



                 </div> -->



             </div>




         </div>

     </div>




 </div>