import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { permissionsIndices } from '../../shared/constants/permissions';
// import { FirebaseService } from '../../shared/services/firebase.service';
import { GlobalFunctions } from '../../shared/services/globalFunctions';
import { HomeService } from '../../shared/services/home.service';
import * as Chartist from 'chartist';

@Component({
	selector: 'app-dashboard',
	templateUrl: './dashboard.component.html',
	styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent implements OnInit {

	isAllowed = true;
	totalUsers = 0;
	usedSickLeaves = 0;
	remainingSickLeaves = 0;
	usedCasualLeaves = 0;
	remainingCasualLeaves = 0;
	administratorCount = 0;
	todayCheckIn = "--";
	todayCheckOut = "--";
	rolesCount = 0;
	appUsers = {
		ios: 0,
		android: 0
	};

	recentUsers = [];
	periodGraph = {
		count: 0,
		percentage: 0,
		graph: {
			dates: [],
			count: []
		}
	};
	constructor(private globalFunctions: GlobalFunctions, private homeService: HomeService, private router: Router) { }

	startAnimationForLineChart(chart) {
		let seq: any, delays: any, durations: any;
		seq = 0;
		delays = 80;
		durations = 500;

		chart.on('draw', function (data) {
			if (data.type === 'line' || data.type === 'area') {
				data.element.animate({
					d: {
						begin: 600,
						dur: 700,
						from: data.path.clone().scale(1, 0).translate(0, data.chartRect.height()).stringify(),
						to: data.path.clone().stringify(),
						easing: Chartist.Svg.Easing.easeOutQuint
					}
				});
			} else if (data.type === 'point') {
				seq++;
				data.element.animate({
					opacity: {
						begin: seq * delays,
						dur: durations,
						from: 0,
						to: 1,
						easing: 'ease'
					}
				});
			}
		});

		seq = 0;
	};

	ngOnInit() {
		// this.checkViewGuard();
		// if (this.isAllowed) {
		this.homeService.dashboarDetails().subscribe(res => {
			//console.log(res.data)
			this.totalUsers = res.data.totalUsers;
			this.todayCheckIn = res.data.todayCheckIn;
			this.todayCheckOut = res.data.todayCheckOut;
			if (res.data.leave_quota) {
				this.usedSickLeaves = res.data.leave_quota.used_sick_leaves ? res.data.leave_quota.used_sick_leaves : 0;
				this.remainingSickLeaves = res.data.leave_quota.remaining_sick_leaves ? res.data.leave_quota.remaining_sick_leaves : 0;
				this.usedCasualLeaves = res.data.leave_quota.used_casual_leaves ? res.data.leave_quota.used_casual_leaves : 0;
				this.remainingCasualLeaves = res.data.leave_quota.remaining_casual_leaves ? res.data.leave_quota.remaining_casual_leaves : 0;
			}
		});
		/* this.homeService.administratorCount().subscribe(res => {
			this.administratorCount = res.data
		});
		this.homeService.rolesCount().subscribe(res => {
			this.rolesCount = res.data
		});
		this.homeService.appUsers().subscribe(res => {
			this.appUsers = res.data
		});
		this.homeService.recentUsers().subscribe(res => {
			this.recentUsers = res.data
		}); */
		/* this.homeService.periodGraph().subscribe(res => {
			this.periodGraph = res.data
			const dataDailySalesChart: any = {
				labels: this.periodGraph.graph.dates,
				series: [
					this.periodGraph.graph.count
				]
			};

			const optionsDailySalesChart: any = {
				lineSmooth: Chartist.Interpolation.cardinal({
					tension: 0
				}),
				low: 0,
				high: 50, // creative tim: we recommend you to set the high sa the biggest value + something for a better look
				chartPadding: { top: 0, right: 0, bottom: 0, left: 0 },
			}

			var dailySalesChart = new Chartist.Line('#dailySalesChart', dataDailySalesChart, optionsDailySalesChart);

			this.startAnimationForLineChart(dailySalesChart);
		}); */
		// }
	}

	/* checkViewGuard() {
		this.isAllowed = this.globalFunctions.canActivateCheck('dashboard', permissionsIndices.VIEW);
	} */

	navigateToModule(path, id?) {
		if (id) {
			path = path + id;
		}
		this.router.navigateByUrl('home/' + path);
	}
}
