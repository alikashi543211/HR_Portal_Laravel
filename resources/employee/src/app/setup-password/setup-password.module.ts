import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { SetupPasswordRoutingModule } from './setup-password-routing.module';
import { MatCard, MatCardModule } from '@angular/material/card';
import { SetupPasswordComponent } from './setup-password.component';
import { MatFormFieldModule } from '@angular/material/form-field';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatInputModule } from '@angular/material/input';
import { MatButtonModule } from '@angular/material/button';
import { MatIconModule } from '@angular/material/icon';
import { MatTooltipModule } from '@angular/material/tooltip';


@NgModule({
    declarations: [],
    imports: [
        CommonModule,
        MatInputModule,
        MatButtonModule,
        MatFormFieldModule,
        MatCardModule,
        MatIconModule,
        // FormsModule,
        // ReactiveFormsModule,
        MatTooltipModule,
    ]
})
export class SetupPasswordModule { }
