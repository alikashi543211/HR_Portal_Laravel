import { Injectable } from '@angular/core';
import { FormGroup } from '@angular/forms';
import { Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { Subscription } from 'rxjs';
import { AuthService } from './auth.service';
// import { FirebaseService } from './firebase.service';
import { HomeService } from './home.service';

@Injectable({
  providedIn: 'root'
})
export class GlobalFunctions {

  constructor(private toastrService: ToastrService, private homeService: HomeService, private router: Router, private authService: AuthService) { }
  // constructor(private toastrService: ToastrService, private homeService: HomeService, private router: Router, private authService: AuthService, private firebaseService: FirebaseService) { }

  // For Guard Checking
  canActivateCheck(key, actionIndex): boolean {
    let permissions: any;
    permissions = this.authService.getPermissions();
    if (this.autoLogOut()) {
      this.signout(true);
      return false;
    }
    if (permissions && permissions[key] && permissions[key][actionIndex]) {
      return true;
    }
    else {
      if (key != 'dashboard') this.toastrService.error('You are not allowed to perform this action.');
      return false;
    }
  }

  signingOut: Subscription;

  signout(type = false) {
    // if type == true => called for showing session expiry toastr
    if (this.signingOut) {
      this.signingOut.unsubscribe();
    }
    if (type) {
      this.toastrService.clear();
      this.toastrService.error('Your session has expired due to inactivity. Login Again to continue.');
    }
    const obj = {
      uuid: localStorage.getItem('uuid')
    };
    this.signingOut = this.authService.logout(obj)
      .subscribe((res: any) => {
        // this.firebaseService.removeSubscrtiption();
        localStorage.clear();
        this.signingOut.unsubscribe();
        // this.homeService.clearRolesList();
        this.router.navigate(['']);
      },
        (e) => {
          this.signingOut.unsubscribe();
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
        });
  }

  // FOR SESSION EXPIRATION: START
  autoLogOut() {
    if (!localStorage.getItem('token')) return false;
    let previousTime: any;
    previousTime = localStorage.getItem('previousTime');
    if (!previousTime) {
      localStorage.setItem('previousTime', Date.now().toString());
      return false;
    } else {
      previousTime = parseInt(previousTime);
      const currentTime = Date.now();
      if (this.diff_minutes(new Date(currentTime), new Date(previousTime)) >= 10) {
        return true;
      } else {
        localStorage.setItem('previousTime', Date.now().toString());
        return false;
      }
    }
  }
  diff_minutes(dt2, dt1) {
    var diff = (dt2.getTime() - dt1.getTime()) / 1000;
    diff /= 60;
    return Math.abs(Math.round(diff));
  }
  // FOR SESSION EXPIRATION: END

  //PWD MISTMATCH VALIDATOR
  comparePassword(
    controlName: string,
    matchingControlName: string
  ) {
    return (formGroup: FormGroup) => {
      const control = formGroup.controls[controlName];
      const matchingControl = formGroup.controls[matchingControlName];

      if (matchingControl.errors && !matchingControl.errors.mustMatch) {
        return;
      }

      if (control.value !== matchingControl.value) {
        matchingControl.setErrors({ mustMatch: true });
      } else {
        matchingControl.setErrors(null);
      }
    };
  }
}
