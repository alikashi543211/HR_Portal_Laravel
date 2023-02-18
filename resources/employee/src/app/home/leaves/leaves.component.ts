import { Component, OnInit } from '@angular/core';
import { HomeService } from 'src/app/shared/services/home.service';

@Component({
    selector: 'app-leaves',
    templateUrl: './leaves.component.html',
    styleUrls: ['./leaves.component.css']
})
export class LeavesComponent implements OnInit {
    public isLoading: boolean = true;
    public leaves;
    constructor(private homeService: HomeService) { }

    ngOnInit(): void {
        this.homeService.leaves().subscribe(res => {
            this.leaves = res.data;
            this.isLoading = false;
        }, error => {
            console.log(error.message);
        });
    }

}
