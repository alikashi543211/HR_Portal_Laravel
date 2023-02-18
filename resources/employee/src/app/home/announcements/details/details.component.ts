import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { HomeService } from 'src/app/shared/services/home.service';

@Component({
    selector: 'app-details',
    templateUrl: './details.component.html',
    styleUrls: ['./details.component.css']
})
export class DetailsComponent implements OnInit {
    private announceId = 0;
    public details;
    public isLoading: boolean = true;
    constructor(private route: ActivatedRoute, private homeService: HomeService) {
        this.route.params.subscribe(res => {
            this.announceId = res['id'];
        });
    }

    ngOnInit(): void {
        this.homeService.announcementDetails({ 'id': this.announceId }).subscribe(res => {
            this.details = res.data;
            this.isLoading = false;
        }, error => {
            console.log(error);
        })
    }

}
