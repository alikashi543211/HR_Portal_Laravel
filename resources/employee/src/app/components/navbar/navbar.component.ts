import { Component, OnInit, ElementRef, ViewEncapsulation } from '@angular/core';
import { ROUTES } from '../sidebar/sidebar.component';
import { Location } from '@angular/common';
import { Router } from '@angular/router';
// import { FirebaseService } from '../../shared/services/firebase.service';
import { GlobalFunctions } from '../../shared/services/globalFunctions';
import { AuthService } from '../../shared/services/auth.service';

@Component({
    selector: 'app-navbar',
    templateUrl: './navbar.component.html',
    styleUrls: ['./navbar.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class NavbarComponent implements OnInit {
    private listTitles: any[];
    location: Location;
    mobile_menu_visible: any = 0;
    private toggleButton: any;
    private sidebarVisible: boolean;

    userObj = this.authService.getUserObj();

    constructor(location: Location, private authService: AuthService, private globalFunctions: GlobalFunctions, private element: ElementRef, private router: Router) {
        // constructor(location: Location, private authService: AuthService, private globalFunctions: GlobalFunctions, private element: ElementRef, private router: Router, public firebaseService: FirebaseService) {
        this.location = location;
        this.sidebarVisible = false;
    }

    getUserImg() {
        let userObject;
        userObject = this.authService.getUserObj();
        if (userObject && userObject.profile_pic) {
            return userObject.profile_pic;
        } else {
            return null;
        }
    }

    getUserName() {
        let userObject;
        userObject = this.authService.getUserObj();
        if (userObject && userObject.first_name) {
            return userObject.first_name + ' ' + userObject.last_name;
        } else {
            return null;
        }
    }

    ngOnInit() {
        this.authService.userObjSubject.subscribe(() => {
            this.userObj = JSON.parse(localStorage.getItem('userObj'));
        });
        // this.listTitles = ROUTES.filter(listTitle => listTitle);
        this.listTitles = [
            { path: '/home/dashboard', title: 'Dashboard' },
            { path: '/home/my-profile', title: 'My Profile' }
        ];
        const navbar: HTMLElement = this.element.nativeElement;
        this.toggleButton = navbar.getElementsByClassName('navbar-toggler')[0];
        this.router.events.subscribe((event) => {
            this.sidebarClose();
            var $layer: any = document.getElementsByClassName('close-layer')[0];
            if ($layer) {
                $layer.remove();
                this.mobile_menu_visible = 0;
            }
        });
    }

    sidebarOpen() {
        const toggleButton = this.toggleButton;
        const body = document.getElementsByTagName('body')[0];
        setTimeout(function () {
            toggleButton.classList.add('toggled');
        }, 500);

        body.classList.add('nav-open');

        this.sidebarVisible = true;
    };
    sidebarClose() {
        const body = document.getElementsByTagName('body')[0];
        this.toggleButton.classList.remove('toggled');
        this.sidebarVisible = false;
        body.classList.remove('nav-open');
    };
    sidebarToggle() {
        // const toggleButton = this.toggleButton;
        // const body = document.getElementsByTagName('body')[0];
        var $toggle = document.getElementsByClassName('navbar-toggler')[0];

        if (this.sidebarVisible === false) {
            this.sidebarOpen();
        } else {
            this.sidebarClose();
        }
        const body = document.getElementsByTagName('body')[0];

        if (this.mobile_menu_visible == 1) {
            // $('html').removeClass('nav-open');
            body.classList.remove('nav-open');
            if ($layer) {
                $layer.remove();
            }
            setTimeout(function () {
                $toggle.classList.remove('toggled');
            }, 400);

            this.mobile_menu_visible = 0;
        } else {
            setTimeout(function () {
                $toggle.classList.add('toggled');
            }, 430);

            var $layer = document.createElement('div');
            $layer.setAttribute('class', 'close-layer');


            if (body.querySelectorAll('.main-panel')) {
                document.getElementsByClassName('main-panel')[0].appendChild($layer);
            } else if (body.classList.contains('off-canvas-sidebar')) {
                document.getElementsByClassName('wrapper-full-page')[0].appendChild($layer);
            }

            setTimeout(function () {
                $layer.classList.add('visible');
            }, 100);

            $layer.onclick = function () { //asign a function
                body.classList.remove('nav-open');
                this.mobile_menu_visible = 0;
                $layer.classList.remove('visible');
                setTimeout(function () {
                    $layer.remove();
                    $toggle.classList.remove('toggled');
                }, 400);
            }.bind(this);

            body.classList.add('nav-open');
            this.mobile_menu_visible = 1;

        }
    };

    getTitle() {
        var titlee = this.location.prepareExternalUrl(this.location.path());
        if (titlee.charAt(0) === '#') {
            titlee = titlee.slice(1);
        }

        let result = '';

        for (var item = 0; item < this.listTitles.length; item++) {
            if (titlee.indexOf(this.listTitles[item].path) > -1) {
                result = this.listTitles[item].title;
            }
        }
        return result;
    }

    signout() {
        this.globalFunctions.signout();
    }

    myProfile() {
        this.router.navigate(['/home/my-profile']);
    }
}
