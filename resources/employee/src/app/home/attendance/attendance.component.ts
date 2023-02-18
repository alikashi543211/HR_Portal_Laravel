import { Component, OnInit, ViewChild, ViewEncapsulation } from '@angular/core';
import { FormGroup, FormBuilder } from '@angular/forms';
import { MatPaginator } from '@angular/material/paginator';
import { Router } from '@angular/router';
import { HomeService } from '../../shared/services/home.service';
import { ToastrService } from 'ngx-toastr';
import { merge, Subscription } from 'rxjs';
import { debounceTime } from 'rxjs/operators';


@Component({
  selector: 'app-attendance',
  templateUrl: './attendance.component.html',
  styleUrls: ['./attendance.component.css'],
  encapsulation: ViewEncapsulation.None
})
export class AttendanceComponent implements OnInit {

  attendanceListing = [];
  attendanceTypes = [];
  attendanceData: [];
  days = [];

  @ViewChild(MatPaginator) paginator: MatPaginator;
  constructor(private homeService: HomeService, private router: Router,
    private toastrService: ToastrService, private formBuilder: FormBuilder) { }

  ngOnInit() {

    this.homeService.attendanceListing()
      .subscribe((res: any) => {
        //console.log(res);
        for (let i = 0; i < 31; i++) {
          this.days[i] = i + 1;

        }
        // console.log(res.data.attendance)
        this.attendanceListing = res.data;
        this.attendanceTypes = res.data.types;
        this.attendanceData = res.data.attendance;
      },
        (e) => {
          if (typeof (e) == 'string') {
            this.toastrService.error(e);
          } else {
            this.toastrService.error(e[0]);
          }
        });
  }

}
