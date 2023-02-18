import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { HomeService } from '../../../shared/services/home.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-details',
  templateUrl: './details.component.html',
  styleUrls: ['./details.component.css']
})
export class DetailsComponent implements OnInit {

  getDate: any;
  monthAttendance: [];
  total: [];

  constructor(private activatedRoute: ActivatedRoute, private homeService: HomeService, private toastrService: ToastrService, private router: Router) {
    this.activatedRoute.params.subscribe((params: any) => {
      this.getDate = params.date;
    });
  }

  ngOnInit() {

    // const getNewDate = new Date(this.getDate);
    // this.getDate = getNewDate.getMonth() + "-" + getNewDate.getFullYear();
    // console.log(this.getDate);
    const obj = {
      date: this.getDate
    };

    this.homeService.attendanceDetails(obj)
      .subscribe((res: any) => {
        if (res.data) {
          this.monthAttendance = res.data.attendance;
          this.total = res.data.total;
        }
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
