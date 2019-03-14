import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ViewsRoutingModule } from './views-routing.module';
import { BaseComponent } from './base/base.component';
import { DashboardComponent } from './dashboard/dashboard.component';
import { NgxFoundationModule } from '../shared/';
import { UtilComponentsModule } from '../util';

@NgModule({
  declarations: [BaseComponent, DashboardComponent],
  imports: [
    CommonModule,
    NgxFoundationModule,
    UtilComponentsModule,
    ViewsRoutingModule
  ]
})
export class ViewsModule { }
