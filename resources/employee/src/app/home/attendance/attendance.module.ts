import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { AttendanceComponent } from './attendance.component';
import { RouterModule } from '@angular/router';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { MatButtonModule } from '@angular/material/button';
import { MatDialogModule } from '@angular/material/dialog';
import { MatFormFieldModule } from '@angular/material/form-field';
import { MatIconModule } from '@angular/material/icon';
import { MatInputModule } from '@angular/material/input';
import { MatSelectModule } from '@angular/material/select';
import { MatSlideToggleModule } from '@angular/material/slide-toggle';
import { MatSortModule } from '@angular/material/sort';
import { MatTableModule } from '@angular/material/table';
import { MatTabsModule } from '@angular/material/tabs';
import { ConfirmModalModule } from '../../components/confirm-modal/confirm-modal.module';
import { ConfirmModalComponent } from '../../components/confirm-modal/confirm-modal.component';
import { MatPaginatorModule } from '@angular/material/paginator';
import { MatTooltipModule } from '@angular/material/tooltip';
import { AttendanceRoutingModule } from './attendance-routing.module';
import { DetailsComponent } from './details/details.component';

@NgModule({
  declarations: [AttendanceComponent, DetailsComponent],
  imports: [
    CommonModule,
    MatIconModule,
    MatButtonModule,
    FormsModule,
    ReactiveFormsModule,
    MatSelectModule,
    MatFormFieldModule,
    MatInputModule,
    MatSlideToggleModule,
    MatTableModule,
    MatSortModule,
    MatTabsModule,
    ConfirmModalModule,
    MatDialogModule,
    MatPaginatorModule,
    MatTooltipModule,
    AttendanceRoutingModule
  ],
  entryComponents: [ConfirmModalComponent]
})
export class AttendanceModule { }
