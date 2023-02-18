import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { LeavesRoutingModule } from './leaves-routing.module';
import { LeavesComponent } from './leaves.component';
import { NewRequestComponent } from './new-request/new-request.component';
import { ReactiveFormsModule } from '@angular/forms';
import { MatInput, MatInputModule } from '@angular/material/input';
import { MatRadioModule } from '@angular/material/radio';
import { MatDatepickerModule } from '@angular/material/datepicker';
import { MatIconModule } from '@angular/material/icon';
import { MatNativeDateModule } from '@angular/material/core';
import { DetailsComponent } from './details/details.component';


@NgModule({
    declarations: [LeavesComponent, NewRequestComponent, DetailsComponent],
    imports: [
        CommonModule,
        LeavesRoutingModule,
        ReactiveFormsModule,
        MatInputModule,
        MatIconModule,
        MatRadioModule,
        MatDatepickerModule, MatNativeDateModule
    ]
})
export class LeavesModule { }
