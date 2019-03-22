import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { UtilAccordionMenuComponent, UtilTopBarComponent } from './';
import { RouterModule } from '@angular/router';

@NgModule({
  declarations: [
    UtilAccordionMenuComponent,
    UtilTopBarComponent
  ],
  imports: [
    CommonModule,
    RouterModule
  ],
  exports: [
    UtilAccordionMenuComponent,
    UtilTopBarComponent
  ]
})
export class UtilComponentsModule { }
