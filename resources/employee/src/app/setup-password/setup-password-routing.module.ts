import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { SetupPasswordComponent } from './setup-password.component';

const routes: Routes = [
    {
        path: '',
        component: SetupPasswordComponent
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class SetupPasswordRoutingModule { }
