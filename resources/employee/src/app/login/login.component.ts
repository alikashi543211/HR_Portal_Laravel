import { Component, OnDestroy, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import { customValidations } from '../shared/constants/validations.constants';
import { AuthService } from '../shared/services/auth.service';
// import { FirebaseService } from '../shared/services/firebase.service';
import { GlobalFunctions } from '../shared/services/globalFunctions';
import { ToastrService } from 'ngx-toastr';
import { iconsList } from '../shared/constants/loginScreenIcons';
import { ValidationService } from '../shared/services/validation.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class LoginComponent implements OnInit, OnDestroy {

  loginForm: FormGroup;
  newPasswordForm: FormGroup;
  matIconNames = [];
  step = 1;

  randomlySelectedIndex: number;
  selectedIconIndex = null;

  counter: number;
  countDownRef: any;

  public settings = {
    length: 4,
    numbersOnly: true,
    btnClass: 'hideTimer'
  };

  otpCode: string = '';

  businessRefId: string;
  counterTime = 60;

  verifyCode: string = '';

  wrongIconClickCount = 0;

  number = '';

  pwdShowHide = [
    false, // step1 
    false, // reset pwd
    false // confirm reset pwd
  ];

  forgotEmailControl = new FormControl('', [Validators.required, Validators.email, Validators.maxLength(50)]);

  constructor(private router: Router, private formBuilder: FormBuilder,
    // constructor(private router: Router, private formBuilder: FormBuilder, private firebaseService: FirebaseService,
    public validationService: ValidationService, private authService: AuthService,
    private toaster: ToastrService, private globalFunctions: GlobalFunctions) {
  }

  showHidePwd(index, event) {
    this.pwdShowHide[index] = !this.pwdShowHide[index];
    event.stopPropagation();
  }

  ngOnInit(): void {

    localStorage.clear();
    /*  const uuid = this.firebaseService.getUuid();
      localStorage.setItem('uuid', uuid); */

    this.loginForm = this.formBuilder.group({
      email: ['', [Validators.required, Validators.email, Validators.maxLength(50)]],
      password: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(30)]]
    });

    this.newPasswordForm = this.formBuilder.group({
      newPassword: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(30), Validators.pattern(customValidations.passwordRegex)]],
      confirmPassword: ['']
    },
      {
        validator: this.globalFunctions.comparePassword("newPassword", "confirmPassword")
      });

    this.resetStep1();
  }

  ngOnDestroy() {
    clearInterval(this.countDownRef);
  }

  resetStep1() {
    this.pwdShowHide = [
      false, // step1 
      false, // reset pwd
      false // confirm reset pwd
    ];
    this.loginForm.get('password').reset();
    this.loginForm.markAsUntouched();
    this.loginForm.markAsPristine();
    this.newPasswordForm.reset();
    this.newPasswordForm.markAsPristine();
    this.newPasswordForm.markAsUntouched();
    this.forgotEmailControl.reset();
  }

  get title(): string {
    let title = '';
    switch (this.step) {
      case 1:
        title = 'Log on to your account';
        break;
      case 2:
        title = 'OTP CODE';
        break;
      case 3:
        title = 'Forgot password?';
        break;
      case 4:
        title = 'Reset your password';
        break;
      default:
        title = '';
        break;
    }
    return title;
  }

  onOtpChange(e) {
    if (this.step === 2)
      this.otpCode = e;
    else this.verifyCode = e;
  }

  checkOtpBtn(): boolean {
    if (this.step === 2) {
      if (!this.otpCode.length || this.otpCode.length < 4) {
        return true;
      } else {
        return false;
      }
    } else {
      if (!this.verifyCode.length || this.verifyCode.length < 4) {
        return true;
      } else {
        return false;
      }
    }
  }

  goToDashboard(responseObj): void {
    this.authService.storeUserData(responseObj.token, responseObj.user, responseObj.permissions);
    this.router.navigate(['/home']);
  }

  get controls() {
    return this.loginForm.controls;
  }

  get pwdControls() {
    return this.newPasswordForm.controls;
  }

  confirmPwdCheck() {
    if (this.newPasswordForm.invalid) return true;
    else return false;
  }

  startCountDown(n) {
    if (n <= 0) {
      return;
    }
    clearInterval(this.countDownRef);
    this.counter = n;
    this.countDownRef = setInterval(() => {
      this.counter--;
      if (this.counter == 0) {
        clearInterval(this.countDownRef);
      }
    }, 1000);
  }

  login() {
    const obj = this.loginForm.value;
    /* if (!localStorage.getItem('uuid')) {
      const uuid = this.firebaseService.getUuid();
      localStorage.setItem('uuid', uuid);
    }
    obj.uuid = localStorage.getItem('uuid'); */
    this.authService.login(obj)
      .subscribe((res: any) => {
        if (res.data && res.data.twoFA) {
          this.toaster.success(res.message);
          this.step = 5;
          this.startCountDown(this.counterTime);
          this.number = res.data.number;
        } else {
          this.toaster.success(res.message);
          this.goToDashboard(res.data);
        }
      },
        (e) => {
          if (e.error) {
            let msg = e.error.message;
            if (e.error.data.remaining_time) msg = msg + ' Remaining time: ' + this.transform(e.error.data.remaining_time)
            this.toaster.error(msg);
            return;
          }
          if (typeof (e) == 'string') {
            this.toaster.error(e);
          } else if (e[0]) {
            this.toaster.error(e[0]);
          }
        });
  }

  verifyOtpForLogin() {
    const obj = this.loginForm.value;
    obj.code = this.verifyCode;
    /* this.authService.verifyLoginOtp(obj)
      .subscribe((res: any) => {
        this.toaster.success(res.message);
        this.goToDashboard(res.data);
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toaster.error(e);
          } else {
            this.toaster.error(e[0]);
          }
        }); */
  }

  sendOtp() {
    const obj = {
      email: this.forgotEmailControl.value
    };
    /* this.authService.sendForgotPwdEmail(obj)
      .subscribe((res: any) => {
        this.number = '';
        this.toaster.success(res.message);
        this.step = 2;
        this.businessRefId = res.data.business_reference_id;
        this.forgotEmailControl.reset();
        this.startCountDown(this.counterTime);
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toaster.error(e);
          } else {
            this.toaster.error(e[0]);
          }
        }); */
  }

  reSendOtp() {
    if (this.counter > 0) return;
    if (this.step === 2) {
      const obj = {
        business_reference_id: this.businessRefId
      };
      /* this.authService.resendOtp(obj)
        .subscribe((res: any) => {
          this.toaster.success(res.message);
          this.businessRefId = res.data.business_reference_id;
          this.startCountDown(this.counterTime);
        },
          (e) => {
            if (typeof (e) == 'string') {
              this.toaster.error(e);
            } else {
              this.toaster.error(e[0]);
            }
          }); */
    }
    else {
      this.login();
    }
  }

  verifyOtp() {
    if (this.step === 2) this.verifyOtpForResetPwd();
    else this.verifyOtpForLogin();
  }

  verifyOtpForResetPwd() {
    const obj = {
      code: this.otpCode,
      business_reference_id: this.businessRefId
    };
    /* this.authService.verifyOtp(obj)
      .subscribe((res: any) => {
        this.toaster.success(res.message);
        this.newPasswordForm.reset();
        this.step = 4;
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toaster.error(e);
          } else {
            this.toaster.error(e[0]);
          }
        }); */
  }

  setNewPwd() {

    const obj = {
      code: this.otpCode,
      business_reference_id: this.businessRefId,
      password: this.newPasswordForm.get('newPassword').value,
      password_confirmation: this.newPasswordForm.get('confirmPassword').value
    };
    /* this.authService.resetPwd(obj)
      .subscribe((res: any) => {
        this.resetStep1();
        localStorage.clear();
        this.step = 1;
        this.toaster.success(res.message);
        // this.goToDashboard(res.data);
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toaster.error(e);
          } else {
            this.toaster.error(e[0]);
          }
        }); */
  }

  transform(time: number): string {

    if (time >= 60) {
      const minutes: number = Math.floor(time / 60);
      return minutes + 'm : ' + (time - minutes * 60) + 's';
    } else {
      return time + 's';
    }
  }
}
