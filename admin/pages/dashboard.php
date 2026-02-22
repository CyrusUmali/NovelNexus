 <div class="dashboard" id="dashboard">
     <div class="top">
         <div class="left">
             <h1 class="txt1" onclick="remindNearingOverdue()">Hello,
                 <span style="color: mediumaquamarine;">Admin!</span>
             </h1>
             <p class="date">

                 <?php
                    // Define the timezone
                    date_default_timezone_set('Asia/Manila');

                    // Generate the formatted date and time
                    echo '<p class="date">' . date("M d, Y | l, h:i A") . '</p>';
                    ?>



             </p>
         </div>
         <select name="month" id="month" onchange="changeMonth()">
             <?php
                // Set the timezone to Philippines
                date_default_timezone_set('Asia/Manila');

                // Get the current month number (1-12)
                $currentMonth = date("n");

                // Loop through the months up to the current month
                for ($i = $currentMonth; $i > 0; $i--) {
                    // Format the month name (e.g., January)
                    $monthName = date("F", mktime(0, 0, 0, $i, 1));

                    // Mark the current month as the default selected
                    $selected = ($i == $currentMonth) ? 'selected' : '';

                    echo "<option value=\"$i\" $selected>$monthName</option>";
                }
                ?>
         </select>


     </div>

     <div class="row1">
         <div class="item">
             <div>
                 <span id="paidMembers"></span> <i class='bx bx-user'></i>
             </div>
             <label for="">Paid Subscription</label>
         </div>
         <div class="item">
             <div>
                 <span id="borrowedBooks"></span> <i class='bx bx-book-reader'></i>
             </div>
             <label for="">Borrowed Books</label>
         </div>
         <div class="item">
             <div>
                 <span id="overdueBooks"></span> <i class='bx bx-time-five'></i>
             </div>
             <label for="">Overdue Books</label>
         </div>
         <div class="item">
             <div>
                 <span id="usersCreated"></span> <i class='bx bx-user-plus'></i>
             </div>
             <label for="">New Members</label>
         </div>
     </div>

     <div class="row3">
         <div class="sec-header">
             <h3>Popular Books</h3>
         </div>
         <div class="sec-body">
             <!-- Popular books will be dynamically rendered here -->
         </div>
     </div>
 
         <div class="row2">


             <h3>Overdue Book List</h3>

             <table class="overdueTbl">

                 <thead>
                     <tr>
                         <td>User ID</td>
                         <td>Member</td>
                         <td>Book Title</td>
                         <td>Start Date</td>
                         <td>Return Date</td>
                         <td>Overdue</td>
                         <td>Fine</td>
                     </tr>
                 </thead>

                 <tbody id="overdueTbody">

                 </tbody>
             </table>


             </table>



             <div class="bottom" id="overdueTablePaginationContainer">
                 <ul class="page-ctrl" id="overduePagination">
                     <!-- <a href="#" id="prev">
                    <li><i class='bx bx-chevron-left'></i></li>
                </a> -->
                     <!-- Dynamic page numbers will be inserted here -->
                     <!-- <a href="#" id="next">
                    <li><i class='bx bx-chevron-right'></i></li>
                </a> -->
                 </ul>
             </div>



         </div>
          




     <div class="row4">

         <div class="item">

             <h3>Payment Reports</h3>

             <div class="body">

                 <div class="left">

                     <div class="desc">

                         <span id="paymentCollection"> ₱300.00</span>
                         <label for="">Last Month Collection</label>

                     </div>
                     <div class="legend">

                         <div class="Lrow">

                             <i class="mark1"></i> <label for="">Subscription</label>


                         </div>

                         <div class="Lrow">


                             <i class="mark2"></i> <label for="">Fines</label>

                         </div>


                     </div>

                 </div>

                 <div class="payment-report">
                     <canvas class="payment-chart"></canvas>
                 </div>



             </div>


         </div>


         <div class="item">

             <h3>Book Category Popularity</h3>

             <div class="body">


                 <div class="category-report">
                     <canvas id="categoryChart" ></canvas>
                 </div>



             </div>


         </div>


         <div class="item">

             <h3>Book availability</h3>

             <div class="body">

                 <div class="left">

                     <div class="desc">


                     </div>
                     <div class="legend">

                         <div class="Lrow">

                             <i class="mark1"></i> <label for="">Loaned</label>


                         </div>

                         <div class="Lrow">


                             <i class="mark2"></i> <label for="">Available</label>

                         </div>


                     </div>

                 </div>

                 <div class="book-availability-report">


                     <canvas class="book-availability-chart"></canvas>



                 </div>



             </div>


         </div>


     </div>

     <div class="row2" >


         <h3>Payment List</h3>

         <table class="overdueTbl">

             <thead>
                 <tr>
                     <td>User ID</td>
                     <td>Member</td>
                     <td>Payment For</td>
                     <td>Amount</td>
                     <td>Date</td>
                 </tr>
             </thead>

             <tbody id="paymentTbl">

             </tbody>
         </table>


         </table>



         <div class="bottom" id="paymentsPaginationContainer">
             <ul class="page-ctrl" id="paymentPagination">
                 <!-- <a href="#" id="prev">
                    <li><i class='bx bx-chevron-left'></i></li>
                </a> -->
                 <!-- Dynamic page numbers will be inserted here -->
                 <!-- <a href="#" id="next">
                    <li><i class='bx bx-chevron-right'></i></li>
                </a> -->
             </ul>
         </div>



     </div>

     <div class="spacer">

      <h1>Spacer</h1>
        
     </div>


 </div>