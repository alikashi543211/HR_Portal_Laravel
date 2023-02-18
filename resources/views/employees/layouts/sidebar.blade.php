 <div class="deznav">
     <div class="deznav-scroll">
         <ul class="metismenu" id="menu">
             <li class="{{ Route::is('employee.dashboard') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.dashboard') }}">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Dashboard.svg') }}" alt="">
                     <span class="nav-text dashboard-menu">Dashboard</span>
                 </a>

             </li>
             <li class="{{ Route::is('employee.attendance.*') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.attendance.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Attendance.svg') }}" alt="">
                     <span class="nav-text">Attendance</span>
                 </a>
                 {{-- <ul aria-expanded="false">
                     <li><a href="app-profile.html">Profile</a></li>
                     <li><a href="post-details.html">Post Detail</a></li>
                     <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Email</a>
                         <ul aria-expanded="false">
                             <li><a href="email-compose.html">Compose</a></li>
                             <li><a href="email-inbox.html">Inbox</a></li>
                             <li><a href="email-read.html">Read</a></li>
                         </ul>
                     </li>
                     <li><a href="app-calender.html">Calendar</a></li>
                     <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Shop</a>
                         <ul aria-expanded="false">
                             <li><a href="ecom-product-grid.html">Product Grid</a></li>
                             <li><a href="ecom-product-list.html">Product List</a></li>
                             <li><a href="ecom-product-detail.html">Product Details</a></li>
                             <li><a href="ecom-product-order.html">Order</a></li>
                             <li><a href="ecom-checkout.html">Checkout</a></li>
                             <li><a href="ecom-invoice.html">Invoice</a></li>
                             <li><a href="ecom-customers.html">Customers</a></li>
                         </ul>
                     </li>
                 </ul> --}}
             </li>

             <li class="{{ Route::is('employee.leaves.*') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.leaves.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Leave.svg') }}" alt="">
                     <span class="nav-text">Leaves</span>
                 </a>
             </li>

             <li class="{{ Route::is('employee.announcements.listing') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.announcements.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Announcements.svg') }}" alt="">
                     <span class="nav-text">Announcements</span>
                 </a>
             </li>
             <li class="{{ Route::is('employee.loans.*') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.loans.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Loan.svg') }}" alt="">
                     <span class="nav-text">Loan</span>
                 </a>
             </li>
             <li class="{{ Route::is('employee.salary-history.*') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.salary-history.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Salary_History.svg') }}" alt="">
                     <span class="nav-text">Salary History</span>
                 </a>
             </li>
             <li class="{{ Route::is('employee.inventories.*') ? 'mm-active' : '' }}">
                 <a class=" ai-icon" href="{{ route('employee.inventories.listing') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/My_Devices.svg') }}" alt="">
                     <span class="nav-text">My Devices</span>
                 </a>
             </li>
             <li class="{{ Route::is('employee.wiki-login') ? 'mm-active' : '' }}"><a class=" ai-icon" href="{{ route('employee.wiki-login') }}" aria-expanded="false" target="_blank">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/wiki_Icon.svg') }}" alt="">
                     <span class="nav-text">Knowledge Base</span>
                 </a>
             </li>
             <li class="{{ Route::is('employee.profile.detail') ? 'mm-active' : '' }}"><a class="ai-icon" href="{{ route('employee.profile.detail') }}" aria-expanded="false">
                     <img class="svg custom-icon" src="{{ asset('employeesAsset/icons/Profile.svg') }}" alt="">

                     <span class="nav-text">Profile</span>
                 </a>
             </li>

         </ul>


     </div>
 </div>
