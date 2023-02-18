import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import { DetailsComponent } from './details/details.component';
import { LeavesComponent } from './leaves.component';
import { NewRequestComponent } from './new-request/new-request.component';

const routes: Routes = [
    {
        path: '',
        component: LeavesComponent
    },
    {
        path: 'request',
        component: NewRequestComponent
    },
    {
        path: 'details/:id',
        component: DetailsComponent
    }
];

@NgModule({
    imports: [RouterModule.forChild(routes)],
    exports: [RouterModule]
})
export class LeavesRoutingModule { }
