import { Routes } from '@angular/router';
import { ViewGuard } from '../shared/guards/permissions/View.guard';
import { HomeComponent } from './home.component';

export const routes: Routes = [
    {
        path: '',
        component: HomeComponent,
        children: [
            { path: 'dashboard', loadChildren: () => import('./dashboard/dashboard.module').then(m => m.DashboardModule) },
            { path: 'attendance', loadChildren: () => import('./attendance/attendance.module').then(m => m.AttendanceModule) },
            { path: 'my-profile', loadChildren: () => import('./update-profile/update-profile.module').then(m => m.UpdateProfileModule) },
            { path: 'announcements', loadChildren: () => import('./announcements/announcements.module').then(m => m.AnnouncementsModule) },
            { path: 'leaves', loadChildren: () => import('./leaves/leaves.module').then(m => m.LeavesModule) },
            // { path: 'my-profile', loadChildren: './update-profile/update-profile.module#UpdateProfileModule' },
            { path: '', redirectTo: 'dashboard' }
        ],
    }
];
