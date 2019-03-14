import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UtilAccordionMenuComponent } from './';
import { RouterModule } from '@angular/router';

@NgModule({
  declarations: [
    UtilAccordionMenuComponent
  ],
  imports: [
    CommonModule,
    RouterModule
  ],
  exports: [
    UtilAccordionMenuComponent
  ]
})
export class UtilComponentsModule { }
