import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ToastrService } from 'ngx-toastr';
import { customValidations } from '../shared/constants/validations.constants';
import { AuthService } from '../shared/services/auth.service';
import { GlobalFunctions } from '../shared/services/globalFunctions';
import { HomeService } from '../shared/services/home.service';

@Component({
    selector: 'app-setup-password',
    templateUrl: './setup-password.component.html',
    styleUrls: ['./setup-password.component.css'],
    encapsulation: ViewEncapsulation.None
})
export class SetupPasswordComponent implements OnInit {
    public step = 1;
    public newPasswordForm: FormGroup;
    private verificationCode = '';
    public isLoading: boolean = true;
    public codeVerified = false;
    public userId;
    pwdShowHide = [
        false, // step1 
        false, // reset pwd
        false // confirm reset pwd
    ];
    constructor(private formBuilder: FormBuilder, private globalFunctions: GlobalFunctions, private route: ActivatedRoute, private homeService: HomeService, private authService: AuthService, private toaster: ToastrService, private router: Router) {
        this.route.queryParams.subscribe(res => {
            this.verificationCode = res.code;
            this.checkVerificationCode();
            this.initForm();
        })
    }

    ngOnInit(): void {

    }

    initForm() {
        this.newPasswordForm = this.formBuilder.group({
            newPassword: ['', [Validators.required, Validators.minLength(6), Validators.maxLength(30), Validators.pattern(customValidations.passwordRegex)]],
            confirmPassword: [''],
            code: [this.verificationCode],
            user_id: [this.userId]
        },
            {
                validator: this.globalFunctions.comparePassword("newPassword", "confirmPassword")
            });
    }

    checkVerificationCode() {
        this.homeService.checkVerificationCode({ code: this.verificationCode }).subscribe(res => {
            if (this.verificationCode != undefined)
                this.newPasswordForm.setValue({ newPassword: '', confirmPassword: '', code: this.verificationCode, user_id: res.data.id });
            this.userId = res.data.id;
            this.codeVerified = true;
            this.isLoading = false;
        }, error => {
            this.codeVerified = false;
            this.isLoading = false;
        })
    }

    get pwdControls() {
        return this.newPasswordForm.controls;
    }

    showHidePwd(index, event) {
        this.pwdShowHide[index] = !this.pwdShowHide[index];
        event.stopPropagation();
    }

    confirmPwdCheck() {
        if (this.newPasswordForm.invalid) return true;
        else return false;
    }

    setNewPwd() {
        this.homeService.setPassword(this.newPasswordForm.value).subscribe(res => {
            this.toaster.success(res.message);
            console.log(res);
            this.authService.storeUserData(res.data.token, res.data.user);
            this.router.navigate(['/home']);
        })
    }
}
