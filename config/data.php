<?php
return [
    'admin' => [
        'sideMenu' => [
            'dashboard' => [
                'title' => 'Dashboard',
                'icon' => 'nc-bank',
                'url' => 'admin/dashboard',
                'show' => 1,
                'permission' => 0,
            ],
            'user' => [
                'title' => 'Users',
                'icon' => 'nc-circle-10',
                'url' => 'admin/users/listing',
                'show' => 1,
                'permission' => 1,
            ],
            'attendance' => [
                'title' => 'Attendance',
                'icon' => 'nc-badge',
                'url' => 'admin/attendances/listing',
                'show' => 1,
                'permission' => 2,
            ],
            'late-policy' => [
                'title' => 'Attendance Policy',
                'icon' => 'nc-paper',
                'url' => 'admin/late-policy/listing',
                'show' => 1,
                'permission' => 3,
            ],
            'holiday' => [
                'title' => 'Day Exceptions',
                'icon' => 'nc-calendar-60',
                'url' => 'admin/holidays/listing',
                'show' => 1,
                'permission' => 4,
            ],
            'leave' => [
                'title' => 'Leaves',
                'icon' => 'nc-briefcase-24',
                'url' => 'admin/leaves/listing',
                'show' => 1,
                'permission' => 5,
            ],
            'leave-requests' => [
                'title' => 'Leave Requests',
                'icon' => 'nc-briefcase-24',
                'url' => 'admin/leave-requests/listing',
                'show' => 1,
                'permission' => 12,
            ],
            'announcements' => [
                'title' => 'Announcements',
                'icon' => 'nc-bell-55',
                'url' => 'admin/announcements/listing',
                'show' => 1,
                'permission' => 10,
            ],
            'permission' => [
                'title' => 'Permissions',
                'icon' => 'nc-alert-circle-i',
                'url' => 'admin/permissions/listing',
                'show' => 1,
                'permission' => 6,
            ],
            'allowance' => [
                'title' => 'Allowance',
                'icon' => 'nc-money-coins',
                'url' => 'admin/allowances/listing',
                'show' => 1,
                'permission' => 7,
            ],
            'pay-rolls' => [
                'title' => 'Pay Rolls',
                'icon' => 'nc-tile-56',
                'url' => 'admin/pay-rolls/listing',
                'show' => 1,
                'permission' => 8,
            ],
            'increments' => [
                'title' => 'Increments',
                'icon' => 'nc-money-coins',
                'url' => 'admin/increments/listing',
                'show' => 1,
                'permission' => 9,
            ],
            'inventories' => [
                'title' => 'Inventory',
                'icon' => 'nc-money-coins',
                'url' => 'admin/inventories/listing',
                'show' => 1,
                'permission' => 16,
            ],

            'loans' => [
                'title' => 'Loans',
                'icon' => 'nc-money-coins',
                'url' => 'admin/loans/listing',
                'show' => 1,
                'permission' => 14,
            ],
            'profile' => [
                'title' => 'Profile',
                'icon' => 'nc-single-02',
                'url' => 'admin/profile',
                'show' => 1,
                'permission' => 0,
            ],
            'emails' => [
                'title' => 'Send Email',
                'icon' => 'nc-email-85',
                'url' => 'admin/emails/email',
                'show' => 1,
                'permission' => 11
            ],
            'letters' => [
                'title' => 'Letters',
                'icon' => 'nc-badge',
                'url' => 'admin/letters/listing',
                'show' => 1,
                'permission' => 15
            ],
        ],
        'dashboard' => [
            'get-birthdays' => 'admin/get-birthdays'
        ],
        'user' => [
            'save' => 'admin/users/save',
            'listing' => 'admin/users/listing',
            'edit' => 'admin/users/edit',
            'details' => 'admin/users/details',
            'delete' => 'admin/users/delete',
            'update' => 'admin/users/update',
            'changePolicy' => 'admin/users/change-policy',
            'sendJoinMail' => 'admin/users/send-join-mail',
            'markNotificationAsRead' => 'admin/users/mark-all-as-read',
            'notifications' => 'admin/users/notifications',
        ],
        'attendance' => [
            'save' => 'admin/attendances/save',
            'listing' => 'admin/attendances/listing',
            'edit' => 'admin/attendances/edit',
            'delete' => 'admin/attendances/delete',
            'update' => 'admin/attendances/update',
            'excel-impot' => 'admin/attendances/upload/excel',
            'excel-export' => 'admin/attendances/export/excel',
            'delete-exception' => 'admin/attendances/delete-exception'
        ],
        'late-policy' => [
            'update' => 'admin/late-policy/update',
            'edit' => 'admin/late-policy/edit',
            'save' => 'admin/late-policy/save',
            'delete' => 'admin/late-policy/delete',
            'delete-date' => 'admin/late-policy/delete-date',
            'update-dates' => 'admin/late-policy/update-dates',
        ],
        'holiday' => [
            'save' => 'admin/holidays/save',
            'listing' => 'admin/holidays/listing',
            'edit' => 'admin/holidays/edit',
            'delete' => 'admin/holidays/delete',
            'update' => 'admin/holidays/update'
        ],
        'leave' => [
            'listing' => 'admin/leaves/listing',
            'update' => 'admin/leaves/update',
            'summary' => 'admin/leaves/summary',
        ],
        'leave-request' => [
            'listing' => 'admin/leave-requests/listing',
            'details' => 'admin/leave-requests/details',
            'update' => 'admin/leave-requests/update',
            'accept' => 'admin/leave-requests/accept',
            'reject' => 'admin/leave-requests/reject',
            'delete' => 'admin/leave-requests/delete',
        ],
        'permission' => [
            'save' => 'admin/permissions/save',
            'listing' => 'admin/permissions/listing',
            'edit' => 'admin/permissions/edit',
            'delete' => 'admin/permissions/delete',
            'update' => 'admin/permissions/update'
        ],
        'allowance' => [
            'save' => 'admin/allowances/save',
            'listing' => 'admin/allowances/listing',
            'edit' => 'admin/allowances/edit',
            'delete' => 'admin/allowances/delete',
            'update' => 'admin/allowances/update'
        ],
        'increments' => [
            'save' => 'admin/increments/save',
            'listing' => 'admin/increments/listing',
            'edit' => 'admin/increments/edit',
            'delete' => 'admin/increments/delete',
            'update' => 'admin/increments/update'
        ],
        'inventories' => [
            'listing' => 'admin/inventories/listing',
            'add' => 'admin/inventories/add',
            'save' => 'admin/inventories/save',
            'edit' => 'admin/inventories/edit',
            'delete' => 'admin/inventories/delete',
            'update' => 'admin/inventories/update',
            'request-inventory' => 'admin/inventories/request-inventory'
        ],
        'loans' => [
            'save' => 'admin/loans/save',
            'listing' => 'admin/loans/listing',
            'edit' => 'admin/loans/edit',
            'delete' => 'admin/loans/delete',
            'update' => 'admin/loans/update',
            'status-update' => 'admin/loans/status-update'
        ],
        'announcements' => [
            'save' => 'admin/announcements/save',
            'listing' => 'admin/announcements/listing',
            'edit' => 'admin/announcements/edit',
            'delete' => 'admin/announcements/delete',
            'update' => 'admin/announcements/update',
            'send-mail' => 'admin/announcements/send-email'
        ],

        'emails' => [
            'send' => 'admin/emails/send-mails'
        ],
        'letters' => [
            'store' => 'admin/letters/store',
            'update' => 'admin/letters/update',
            'edit' => 'admin/letters/edit',
            'delete' => 'admin/letters/delete',
            'mail' => 'admin/letters/mail',
        ],
        'payroll' => [
            'save' => 'admin/pay-rolls/save',
            'user-save' => 'admin/pay-rolls/save/user',
            'listing' => 'admin/pay-rolls/listing',
            'detail' => 'admin/pay-rolls/detail',
            'detail-add-user' => 'admin/pay-rolls/detail/add',
            'delete' => 'admin/pay-rolls/delete',
            'userpayrolldelete' => 'admin/pay-rolls/user/delete',
            'userallowancedelete' => 'admin/pay-rolls/user/allowance/delete',
            'userallowancesave' => 'admin/pay-rolls/user/allowance/save',
            'userextradelete' => 'admin/pay-rolls/user/extra/delete',
            'userextrasave' => 'admin/pay-rolls/user/extra/save',
            'usergovrttax' => 'admin/pay-rolls/user/govrt/update',
            'update' => 'admin/pay-rolls/update',
            'pdf' => 'admin/pay-rolls/salary-slip-pdf',
            'pdf-payroll' => 'admin/pay-rolls/payroll-pdf',
            'excel' => 'admin/pay-rolls/salary-slip-excel',
            'salarySlipSingle' => 'admin/pay-rolls/details/salary-slip',
            'recalculate' => 'admin/pay-rolls/recalculate',
            'lock' => 'admin/pay-rolls/lock',
            'attendance-pdf' => 'admin/pay-rolls/attendance-pdf',
            'tax-listing' => 'admin/pay-rolls/tax-listing',
            'tax-add' => 'admin/pay-rolls/tax-add',
            'tax-save' => 'admin/pay-rolls/tax-save',
            'tax-edit' => 'admin/pay-rolls/tax-edit',
            'tax-delete' => 'admin/pay-rolls/tax-delete',
            'tax-update' => 'admin/pay-rolls/tax-update',
        ],
        'companyprofile' => [
            'detail' => 'admin/company/details',
            'edit' => 'admin/company/edit',
            'update' => 'admin/company/update',
        ],
    ],
    'roles' => [
        'super_admin' => [
            'id' => 1,
            'title' => 'Super Admin'
        ],
        'admin' => [
            'id' => 2,
            'title' => 'Admin'
        ],
        'human_resource' => [
            'id' => 3,
            'title' => 'Human Resource'
        ],
        'accountant' => [
            'id' => 4,
            'title' => 'Accountant'
        ],
        'manager' => [
            'id' => 5,
            'title' => 'Manager'
        ],
        'employee' => [
            'id' => 6,
            'title' => 'Employee'
        ],
    ],
    'letter_variables' => [
        'Current_date' => '[Current_date]',
        'Employee_Name' => '[Employee_Name]',
        'Father_Name' => '[Father_Name]',
        'Employee_CNIC' => '[Employee_CNIC]',
        'Company_Name' => '[Company_Name]',
        'Working_From' => '[Working_From]',
        'Employee_Salary' => '[Employee_Salary]',
        'Joining_Date' => '[Joining_Date]',
        'Employee_Designation' => '[Employee_Designation]',
        'Job_Title' => '[Job_Title]',
        'Company_Location' => '[Company_Location]',
        'Probation_Period' => '[Probation_Period]',
        'Working_Days' => '[Working_Days]',
        'HR_Person_Name' => '[HR_Person_Name]',
        'HR_Person_Email' => '[HR_Person_Email]',
        'Contract_Date' => '[Contract_Date]',
        'Employee_Address' => '[Employee_Address]',
    ],
];
