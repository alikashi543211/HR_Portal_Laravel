import { Component, OnInit } from '@angular/core';
import { HomeService } from 'src/app/shared/services/home.service';

@Component({
	selector: 'app-announcements',
	templateUrl: './announcements.component.html',
	styleUrls: ['./announcements.component.css']
})
export class AnnouncementsComponent implements OnInit {
	public announcements;
	public isLoading: boolean = true;
	constructor(private homeService: HomeService) { }

	ngOnInit(): void {
		this.homeService.announcements().subscribe(res => {
			this.announcements = res.data;
			this.isLoading = false;
		}, error => {
			console.log(error.message);
		});

	}

}
