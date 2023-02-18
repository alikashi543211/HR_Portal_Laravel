import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { AnnouncementsRoutingModule } from './announcements-routing.module';
import { AnnouncementsComponent } from './announcements.component';
import { DetailsComponent } from './details/details.component';


@NgModule({
  declarations: [AnnouncementsComponent, DetailsComponent],
  imports: [
    CommonModule,
    AnnouncementsRoutingModule
  ]
})
export class AnnouncementsModule { }
