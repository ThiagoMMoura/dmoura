import { MainMenuControlService } from './../../../services/main-menu-control.service';
import { Component, OnInit, OnDestroy, ElementRef, Renderer2 } from '@angular/core';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-util-top-bar-toggle-button',
  templateUrl: './util-top-bar-toggle-button.component.html',
  styleUrls: ['./util-top-bar-toggle-button.component.scss']
})
export class UtilTopBarToggleButtonComponent implements OnInit, OnDestroy {
  private menuIsOpened: boolean;
  private subscription: Subscription;

  constructor(private el: ElementRef, private render: Renderer2, private mainMenuCtrlService: MainMenuControlService) {
    this.subscription = this.mainMenuCtrlService.menuOpened$.subscribe(
      isOpened => {
        this.render.setStyle(this.el.nativeElement, 'display', isOpened ? 'none' : '');
      }
    );
  }

  openMenu() {
    this.mainMenuCtrlService.openMenu();
  }

  ngOnInit() {
  }

  ngOnDestroy(): void {
    this.subscription.unsubscribe();
  }

}
