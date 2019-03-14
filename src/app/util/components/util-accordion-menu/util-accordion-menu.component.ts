import { Component, OnInit, Input, ElementRef } from '@angular/core';
import { UtilAccordionMenuModel } from './util-accordion-menu.model';

@Component({
  selector: 'app-util-accordion-menu',
  templateUrl: './util-accordion-menu.component.html',
  styleUrls: ['./util-accordion-menu.component.scss']
})
export class UtilAccordionMenuComponent implements OnInit {
  @Input()
  menus: UtilAccordionMenuModel[];

  @Input()
  subitemWithIcon = false;

  constructor() { }

  ngOnInit() {
    console.log(this.menus);
  }
}
