import { Component, OnInit, Input } from '@angular/core';

import {
  NG_VALUE_ACCESSOR,
} from '@angular/forms';

import { ValueAccessorBase } from '../value-accessor-base';

@Component({
  selector: 'app-input-string',
  templateUrl: './input-string.component.html',
  styleUrls: ['./input-string.component.scss'],
  providers: [
    {provide: NG_VALUE_ACCESSOR, useExisting: InputStringComponent, multi: true}
  ],
})
export class InputStringComponent extends ValueAccessorBase<string> implements OnInit {
  @Input()
  disabled: boolean;

  @Input()
  errorMensage: string;

  constructor() {
    super();
  }

  ngOnInit() {
  }

}
