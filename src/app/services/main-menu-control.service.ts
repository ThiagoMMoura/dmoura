import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class MainMenuControlService {
  private openedMenuSource = new Subject<boolean>();

  menuOpened$ = this.openedMenuSource.asObservable();

  constructor() { }

  openMenu() {
    this.openedMenuSource.next(true);
  }

  closeMenu() {
    this.openedMenuSource.next(false);
  }
}
