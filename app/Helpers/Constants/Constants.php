<?php
define("WORKING_DAY", 1);
define("HOLIDAY", 0);

define("DATA_PER_PAGE", 10);
define("SUCCESS", 'success');
define("ERROR", 'error');
define("SUPER_ADMIN", 1);
define("ADMIN", 2);
define("HUMAN_RESOURCE", 3);
define("ACCOUNTANT", 4);
define("MANAGER", 5);
define("EMPLOYEE", 6);
define("URL_ADMIN", 'admin/');
// permission action
define("READ", 1);
define("WRITE", 2);
// permissions
define("USERS", 1);
define("ATTENDANCES", 2);
define("LATE_POLICY", 3);
define("HOLIDAYS", 4);
define("LEAVES", 5);
define("PERMISSIONS", 6);
define("ALLOWANCES", 7);
define("PAY_ROLLS", 8);
define("INCREMENTS", 9);
define("ANNOUNCEMENTS", 10);
define("SEND_EMAIL", 11);
define("LEAVE_REQUEST", 12);
define("LOAN", 14);
define("LETTERS", 15);
define("INVENTORY", 16);


// attendance types
define("CHECK_IN", 'Check In');
define("CHECK_OUT", 'Check Out');

define("PER_MINUTE_POLICY", '0');
define("HALF_DAY_POLICY", '1');


define("EARLY_OUT", 1);
define("LATENESS", 2);
define("PUBLIC_HOLIDAY", 3);
define("PRESENT", 4);
define("ABSENT", 5);
define("ON_LEAVE", 6);
define("WEEKEND", 7);
define("HALF_DAY", 8);
define("NO_LATE_POLICY", 9);
define("NOTHING", 0);
define("EXCEPTION_ATTENDANCE", 10);

// Type
define("SICK_LEAVE", 0);
define("CASUAL_LEAVE", 1);

// Period
define("HALF_DAY_LEAVE", 0);
define("FULL_DAY_LEAVE", 1);

// Period Type
define("FULL_DAY", 0);
define("FIRST_HALF", 1);
define("SECOND_HALF", 2);

// Leave Adjust
define("LEAVE_ADJUST", 1);
define("LEAVE_NOT_ADJUST", 0);

// allowances types
define("ALLOWANCES_FIXED", 1);
define("ALLOWANCES_PERCENTAGE", 2);

define("EXTRA_CONTRIBUTIONS", 'Contribution');
define("EXTRA_DEDUCTIONS", 'Deduction');

// user status
define("USER_STATUS_PERMANENT", 'Permanent');
define("USER_STATUS_1M_PROBATION", '1M Probation');
define("USER_STATUS_2M_PROBATION", '2M Probation');
define("USER_STATUS_3M_PROBATION", '3M Probation');
define("USER_STATUS_TERMINATE", 'Terminate');
define("USER_STATUS_OTHER", 'Other');

// API RESPONSE
define("SUCCESS_200", 200);
define("ERROR_400", 400);
define("ERROR_500", 500);

define("PENDING", 0);
define("ACCEPTED", 1);
define("REJECTED", 2);
define("IN_PROGRESS", 3);
define("COMPLETED", 4);
define("PAID", 5);

// Leave Summary
define('SICK_LEAVE_APPROVED', 1);
define('SICK_LEAVE_NOT_APPROVED', 2);
define('CASUAL_LEAVE_APPROVED', 3);
define('CASUAL_LEAVE_NOT_APPROVED', 4);

//INventory
define('ASSIGNED', 'Assigned');
define('UNASSIGNED', "Un Assigned");

//loan status
define('LOAN_PENDING', 0);
define('LOAN_APPROVED', 1);
define('LOAN_REJECTED', 2);

// invertory status
define('INVENTORY_OFFICE', 0);
define('INVENTORY_HOME', 1);

define('INVT_REQ_HOME_APPROVED', "Approved");
define('INVT_REQ_HOME_REJECTED', "Rejected");
define('INVT_REQ_HOME_PENDING', "Pending");
define('INVT_REQ_OFFICE_APPROVED', "approved");
define('INVT_REQ_OFFICE_REJECTED', "Rejected");
define('INVT_REQ_OFFICE_PENDING', "Pending");
define('INVT_REQ_DEFAULT', "-");

define('INVT_REQ_APPROVED', "Approved");
define('INVT_REQ_REJECTED', "Rejected");
define('INVT_REQ_PENDING', "Pending");


// Notification Type
define('NOTIFICATION_ANNOUNCEMENT', 1);
define('NOTIFICATION_LEAVE', 2);
define('NOTIFICATION_DEVICE', 3);
define('NOTIFICATION_LOAN', 4);
