import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { HomeService } from 'src/app/shared/services/home.service';

@Component({
    selector: 'app-details',
    templateUrl: './details.component.html',
    styleUrls: ['./details.component.css']
})
export class DetailsComponent implements OnInit {
    public isLoading: boolean = true;
    private currentId = 0;
    public leaveDetails;
    public requestForm: FormGroup;

    constructor(private homeService: HomeService, private route: ActivatedRoute, private formBuilder: FormBuilder, private router: Router) {
        this.route.params.subscribe(param => {
            this.currentId = param['id'];
        })
    }

    ngOnInit(): void {
        this.homeService.leaveDetails({ id: this.currentId }).subscribe(res => {
            this.leaveDetails = res.data;
            this.requestForm = this.formBuilder.group({
                id: [this.leaveDetails.id],
                type: [this.leaveDetails.type.toString(), Validators.required],
                period: [this.leaveDetails.period.toString(), Validators.required],
                period_type: [this.leaveDetails.period_type.toString(), Validators.required],
                reason: [this.leaveDetails.reason, Validators.required],
                date: [new Date(this.leaveDetails.date).toISOString(), Validators.required]
            });
            this.isLoading = false;
        }, error => {
            console.log(error.message);
        });
    }

    get requestControls() {
        return this.requestForm.controls;
    }

    submitRequest() {
        this.homeService.updateRequestLeave(this.requestForm.value).subscribe(res => {
            console.log(res);
            this.router.navigateByUrl('/home/leaves');
        }, err => {
            console.log(err);
        });
    }

}
