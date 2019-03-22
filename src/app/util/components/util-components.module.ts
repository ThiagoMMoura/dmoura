import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UtilAccordionMenuComponent, UtilTopBarComponent } from './';
import { RouterModule } from '@angular/router';
import { UtilPageContentWithTopBarComponent } from './util-page-content-with-top-bar/util-page-content-with-top-bar.component';
import { UtilTopBarToggleButtonComponent } from './util-top-bar-toggle-button/util-top-bar-toggle-button.component';

@NgModule({
  declarations: [
    UtilAccordionMenuComponent,
    UtilTopBarComponent,
    UtilPageContentWithTopBarComponent,
    UtilTopBarToggleButtonComponent
  ],
  imports: [
    CommonModule,
    RouterModule
  ],
  exports: [
    UtilAccordionMenuComponent,
    UtilTopBarComponent,
    UtilPageContentWithTopBarComponent,
    UtilTopBarToggleButtonComponent
  ]
})
export class UtilComponentsModule { }
