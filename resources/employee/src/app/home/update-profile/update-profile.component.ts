import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { MatDialog } from '@angular/material/dialog';
import { customValidations } from '../../shared/constants/validations.constants';
import { AuthService } from '../../shared/services/auth.service';
import { GlobalFunctions } from '../../shared/services/globalFunctions';
import { HomeService } from '../../shared/services/home.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-update-profile',
  templateUrl: './update-profile.component.html',
  styleUrls: ['./update-profile.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class UpdateProfileComponent implements OnInit {

  profileForm: FormGroup;
  pwdResetForm: FormGroup;

  userObj: any;
  profilePic = './assets/img/faces/marc.jpg';

  FAmodel: any;
  first_name = "";
  last_name = "";
  email = "";

  pwdShowHide = [
    false, // old pwd
    false, // pwd
    false // confirm pwd
  ];

  constructor(private formBuilder: FormBuilder, private toastrService: ToastrService,
    private homeService: HomeService, private authService: AuthService,
    private dialogRef: MatDialog, private globalFunctions: GlobalFunctions) { }


  showHidePwd(index, event) {
    this.pwdShowHide[index] = !this.pwdShowHide[index];
    event.stopPropagation();
  }

  ngOnInit(): void {

    this.fetchUserData();

    this.initializePwdResetForm();

    this.profileForm = this.formBuilder.group({
      email: [{ value: '', disabled: true }],
      first_name: [{ value: '', disabled: true }],
      last_name: [{ value: '', disabled: true }]
      // first_name: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(30), Validators.pattern(customValidations.nameRegexWithSpace)]],
      // last_name: ['', [Validators.required, Validators.minLength(3), Validators.maxLength(30), Validators.pattern(customValidations.nameRegexWithSpace)]]
    });
  }

  fetchUserData() {
    this.homeService.getProfileDetails()
      .subscribe((res: any) => {
        // console.log(res);
        // this.authService.storeUserData(null, res.data.user, res.data.permissions);

        // this.userObj = this.authService.getUserObj();
        this.userObj = res.data;
        // this.profilePic = this.userObj.profile_pic || './assets/img/faces/marc.jpg';
        this.profilePic = res.data.picture || './assets/img/faces/marc.jpg';

        // this.FAmodel = this.userObj.twoFA;

        this.profileForm.patchValue(this.userObj);
      });
  }

  get controls() {
    return this.profileForm.controls;
  }

  get pwdControls() {
    return this.pwdResetForm.controls;
  }

  initializePwdResetForm() {

    this.pwdResetForm = this.formBuilder.group({
      old_password: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(30)]],
      password: ['', [Validators.required, Validators.minLength(5), Validators.maxLength(30)]],
      password_confirmation: ['']
    },
      {
        validator: this.globalFunctions.comparePassword("password", "password_confirmation")
      });
  }

  resetPwd() {
    if (this.pwdControls.password.value != this.pwdControls.password_confirmation.value) {
      this.toastrService.error("Passwords don't match");
      return;
    }
    const obj = this.pwdResetForm.value;

    this.homeService.updatePassword(obj)
      .subscribe((res: any) => {
        this.toastrService.success(res.message);
        this.pwdResetForm.reset();
        Object.keys(this.pwdControls).forEach(key => {
          this.pwdControls[key].setErrors(null);
        });
        this.pwdShowHide = [
          false, // old pwd
          false, // pwd
          false // confirm pwd
        ];
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
        });

  }

  imageUpload() {
    document.getElementById('uploader').click();
  }

  onFileSelect(event) {
    const files = event.target.files as FileList;
    const file = files[0];

    if (file.size / 1000000 > 5) {
      this.toastrService.error('Maximum image size (5mbs) exceeded.');
      event.target.value = null;
      return;
    }

    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = () => {
      this.uploadFileToServer(reader.result);
      event.target.value = null;
    };
    reader.onerror = error => {
      event.target.value = null;
      this.toastrService.error('Image uploading failed!', 'danger');
    };
  }

  uploadFileToServer(base64img): void {
    const obj = {
      profile_pic: base64img
    };
    /* this.homeService.updateProfilePic(obj)
      .subscribe((res: any) => {
        this.toastrService.success(res.message);
        this.authService.storeUserData(null, res.data);
        this.profilePic = res.data.profile_pic;
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
        }); */

  }

  updateProfileInfo() {
    const obj = this.profileForm.value;
    /* this.homeService.updateProfileDetails(obj)
      .subscribe((res: any) => {
        this.toastrService.success(res.message);
        this.userObj.first_name = this.controls.first_name.value;
        this.userObj.last_name = this.controls.last_name.value;
        this.authService.storeUserData(null, this.userObj);
        this.profileForm.markAsUntouched();
        this.profileForm.markAsPristine();
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
        }); */
  }

  /* changeNumber() {
    let dialog = this.dialogRef.open(UpdatePhoneComponent, {
      width: '500px',
      height: 'auto',
      hasBackdrop: true,
      disableClose: true
    });

    dialog.afterClosed()
      .subscribe((res: any) => {
        if (res) {
          this.FAmodel = true;
          this.userObj.phones = res;
        }
      });
  } */

  update2FA(event) {
    const val = event.checked;
    const obj = {
      status: val
    };
    /* this.homeService.update2FA(obj)
      .subscribe((res: any) => {
        this.toastrService.success(res.message);
        this.userObj.twoFA = val;
        this.authService.storeUserData(null, this.userObj);
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
          setTimeout(() => {
            this.FAmodel = !this.FAmodel;
          }, 500);
        }); */
  }

}
