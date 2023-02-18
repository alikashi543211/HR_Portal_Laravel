import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable } from 'rxjs';
import { environment } from 'src/environments/environment';
import { urls } from '../constants/urls';

@Injectable({
    providedIn: 'root'
})
export class HomeService {

    private roles = [];
    private permissionsList = [];

    constructor(private http: HttpClient) {
    }

    // DASHBOARD
    dashboarDetails(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.dashboardDetails}`, params);
    }

    // Attendance
    attendanceListing(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.attendance}`, params);
    }

    attendanceDetails(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.attendanceDetails}`, params);
    }

    // Announcements
    announcements(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.announcements}`, params);
    }

    announcementDetails(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.announcementDetails}`, params);
    }

    // Leaves
    leaves(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.leaves}`, params);
    }

    // Leaves
    requestLeave(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.requestLeave}`, params);
    }

    leaveDetails(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.leaveDetails}`, params);
    }

    updateRequestLeave(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.updateLeaveDetails}`, params);
    }

    // USER DETAILS
    getProfileDetails(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.profileDetails}`, params);
    }

    updatePassword(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.updatePassword}`, params);
    }

    // Setup Password
    checkVerificationCode(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.checkVerificationCode}`, params);
    }

    setPassword(params = {}): Observable<any> {
        return this.http.post<any>(`${environment.apiUrl + urls.setPassword}`, params);
    }
}
