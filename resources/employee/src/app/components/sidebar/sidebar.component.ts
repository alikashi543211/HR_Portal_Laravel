import { Component, OnInit, ViewEncapsulation } from '@angular/core';
// import { FirebaseService } from '../../shared/services/firebase.service';
import { GlobalFunctions } from '../../shared/services/globalFunctions';

declare const $: any;
declare interface RouteInfo {
    path: string;
    title: string;
    icon: string;
    class: string;
}
export const ROUTES: RouteInfo[] = [
    { path: '/home/dashboard', title: 'Dashboard', icon: 'dashboard', class: '' },
    { path: '/home/attendance', title: 'Attendance', icon: 'aod', class: '' },
    { path: '/home/announcements', title: 'Announcements', icon: 'notifications', class: '' },
    { path: '/home/leaves', title: 'Leaves', icon: 'work_off', class: '' }
];


@Component({
    selector: 'app-sidebar',
    templateUrl: './sidebar.component.html',
    styleUrls: ['./sidebar.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class SidebarComponent implements OnInit {
    menuItems: any[];

    PROFILE_OBJ: RouteInfo = { path: '/home/my-profile', title: 'My Profile', icon: 'person_pin', class: '' };
    SIGN_OUT_OBJ: RouteInfo = { path: '', title: 'Signout', icon: 'exit_to_app', class: '' };

    constructor(private globalFunctions: GlobalFunctions) { }
    // constructor(public firebaseService: FirebaseService, private globalFunctions: GlobalFunctions) { }

    ngOnInit() {
        this.menuItems = ROUTES.filter(menuItem => menuItem);
    }
    isMobileMenu() {
        if ($(window).width() > 991) {
            return false;
        }
        return true;
    };


    signout() {
        this.globalFunctions.signout();
    }
}
