import { NgModule } from '@angular/core';
import { CommonModule, } from '@angular/common';
import { BrowserModule } from '@angular/platform-browser';
import { Routes, RouterModule } from '@angular/router';

import { HomeGuard } from './shared/guards/Home.guard';
import { LoginGuard } from './shared/guards/Login.guard';
import { SetupPasswordComponent } from './setup-password/setup-password.component';

const routes: Routes = [

    {
        path: 'login',
        loadChildren: () => import('./login/login.module').then(m => m.LoginModule),
        // canActivate: [LoginGuard]
    },
    {
        path: 'setup-password',
        // loadChildren: () => import('./setup-password/setup-password.module').then(m => m.SetupPasswordModule),
        component: SetupPasswordComponent,
        canActivate: [LoginGuard]
    },
    {
        path: '',
        redirectTo: 'login',
        pathMatch: 'full',
    },
    {
        path: 'home',
        loadChildren: () => import('./home/home.module').then(m => m.HomeModule),
        canActivate: [HomeGuard]
    }
];

@NgModule({
    imports: [
        CommonModule,
        BrowserModule,
        RouterModule.forRoot(routes, {
            useHash: true
        })
    ],
    exports: [
    ],
})
export class AppRoutingModule { }
