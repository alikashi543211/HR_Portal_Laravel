import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { UpdateProfileComponent } from './update-profile.component';
import { RouterModule } from '@angular/router';
import { routes } from './update-profile-routing.module';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatButtonModule } from '@angular/material/button';
import { MatSelectModule } from '@angular/material/select';
import { MatInputModule } from '@angular/material/input';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatIconModule } from '@angular/material/icon';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatDialogModule } from '@angular/material/dialog';
import { AngularOtpLibModule } from 'angular-otp-box';
import { MatTooltipModule } from '@angular/material/tooltip';


@NgModule({
  declarations: [UpdateProfileComponent],
  imports: [
    CommonModule,
    MatFormFieldModule,
    MatInputModule,
    MatButtonModule,
    MatSelectModule,
    FormsModule,
    ReactiveFormsModule,
    MatIconModule,
    MatSlideToggleModule,
    RouterModule.forChild(routes),
    MatDialogModule,
    AngularOtpLibModule,
    MatTooltipModule
  ],
  entryComponents: []
})
export class UpdateProfileModule { }
