import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ViewsRoutingModule } from './views-routing.module';
import { BaseComponent } from './base/base.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { NgxFoundationModule } from '../shared/';

@NgModule({
  declarations: [BaseComponent, DashboardComponent],
  imports: [
    CommonModule,
    NgxFoundationModule,
    ViewsRoutingModule
  ]
})
export class ViewsModule { }
