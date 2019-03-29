import { InputStringComponent } from './input-string/input-string.component';
import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@NgModule({
  declarations: [
    InputStringComponent
  ],
  imports: [
    CommonModule,
    FormsModule
  ],
  exports: [
    InputStringComponent
  ]
})
export class UtilFieldsModule { }
