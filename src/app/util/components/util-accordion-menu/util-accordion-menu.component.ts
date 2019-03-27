import { Component, OnInit, Input } from '@angular/core';
import { UtilAccordionMenuModel } from './util-accordion-menu.model';
import { Router, RouterEvent, NavigationEnd } from '@angular/router';
import { isNullOrUndefined } from 'util';

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

  private activatedRoute: string;

  constructor(private router: Router) {
    this.router.events.subscribe((ev: RouterEvent) => {
      if (ev instanceof NavigationEnd) {
        if (!isNullOrUndefined(ev.url)) {
          this.activatedRoute = ev.url;
        }
      }
    });
  }

  ngOnInit() {
    this.expandMenuByRoute(this.activatedRoute);
  }

  expandMenuByRoute(url: string) {
    for (const menu of this.menus) {
      menu.expanded = menu.expanded || menu.hasSubUrl(url);
    }
  }
}
