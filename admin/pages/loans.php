 <div class="books " id="loansPage">

     <div class="upper-block">

         <h3>Book Loan Management</h3>


         <div class="section">

             <button onclick="showAddNewLoan()">
                 <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                     <path d="M12 2C6.49 2 2 6.49 2 12C2 17.51 6.49 22 12 22C17.51 22 22 17.51 22 12C22 6.49 17.51 2 12 2ZM16 12.75H12.75V16C12.75 16.41 12.41 16.75 12 16.75C11.59 16.75 11.25 16.41 11.25 16V12.75H8C7.59 12.75 7.25 12.41 7.25 12C7.25 11.59 7.59 11.25 8 11.25H11.25V8C11.25 7.59 11.59 7.25 12 7.25C12.41 7.25 12.75 7.59 12.75 8V11.25H16C16.41 11.25 16.75 11.59 16.75 12C16.75 12.41 16.41 12.75 16 12.75Z" fill="white" />
                 </svg>
                 Add Entry

             </button>

             <div class="section">

                 <div class="search">
                     <i class='bx bx-search'></i>
                     <input type="text" placeholder="Search by Name "  oninput="loanSearch(event)">
                 </div>
             </div>

         </div>

     </div>


     <div class="users-section" style="box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px; border-radius:20px;">

         <div class="row2" style="width: 100%;box-shadow: unset">


             <h3>Borrow History</h3>

             <table class="overdueTbl">
                 <thead>
                     <tr>
                         <td>Loan ID</td>
                         <td>Member</td>
                         <td>Book Title</td>
                         <td>Start Date</td>
                         <td>Return Date</td>
                         <td>Status</td>
                         <td>Fine</td>
                         <td></td>
                     </tr>
                 </thead>

                 <tbody>

                 </tbody>
             </table>


             <div class="bottom">
                 <ul class="page-ctrl" id="loansPagination">
                     
                     <!-- Dynamic page numbers will be inserted here -->
                   
                 </ul>
             </div>



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
             <p class="pnotif-message-text">User Notified</p>
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