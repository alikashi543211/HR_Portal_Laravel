import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { HomeService } from 'src/app/shared/services/home.service';

@Component({
    selector: 'app-new-request',
    templateUrl: './new-request.component.html',
    styleUrls: ['./new-request.component.css']
})
export class NewRequestComponent implements OnInit {
    public isLoading: boolean = true;
    public requestForm: FormGroup;

    constructor(private formBuilder: FormBuilder, private homeService: HomeService, private router: Router) { }

    ngOnInit(): void {
        this.requestForm = this.formBuilder.group({
            type: ["0", Validators.required],
            period: ["0", Validators.required],
            period_type: ["0", Validators.required],
            reason: ['', Validators.required],
            date: [new Date().toISOString(), Validators.required]
        });
    }

    get requestControls() {
        return this.requestForm.controls;
    }

    submitRequest() {
        this.homeService.requestLeave(this.requestForm.value).subscribe(res => {
            console.log(res);
            this.router.navigateByUrl('/home/leaves/');
        }, err => {
            console.log(err);

        })
    }

}
