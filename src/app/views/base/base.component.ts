import { MainMenuControlService } from './../../services/';
import { Component, OnInit, OnDestroy } from '@angular/core';
import { UtilAccordionMenuModel } from 'src/app/util/components';
import { Subscription } from 'rxjs';

@Component({
  selector: 'app-base',
  templateUrl: './base.component.html',
  styleUrls: ['./base.component.scss'],
  providers: [MainMenuControlService]
})
export class BaseComponent implements OnInit, OnDestroy {
  private offcanvas = { isOpen: true };
  private mainMenuCtrlServiceSubscription: Subscription;

  user = { name: 'Web Master' , imgUrl: ''};

  mainMenu: UtilAccordionMenuModel[] = [
    { title: 'Painel de instrumentos', url: '/dashboard', icon: 'dashboard' },
    { title: 'Configuração contatos', url: null, icon: 'contacts', isSubmenu: true, submenu: [
      { title: 'Nova Operadora', url: '', icon: 'no' },
      { title: 'Consulta Operadora', url: 'consulta-operadora', icon: 'co' }
    ]},
    { title: 'Clientes', url: null, icon: 'people', isSubmenu: true, submenu: [
      { title: 'Novo Cliente Juridico', url: 'add-pessoa-juridica', icon: 'nc' },
      { title: 'Consulta Cliente', url: '', icon: 'cc' }
    ]},
    { title: 'Segurança', url: '/seguranca', icon: 'security' }
  ];

  constructor(private mainMenuCtrlService: MainMenuControlService) {
    this.mainMenuCtrlServiceSubscription = mainMenuCtrlService.menuOpened$.subscribe(
      isOpened => {
        this.offcanvas.isOpen = isOpened;
      }
    );
  }

  ngOnInit() {
  }

  ngOnDestroy(): void {
    this.mainMenuCtrlServiceSubscription.unsubscribe();
  }

  mainMenuClose() {
    this.mainMenuCtrlService.closeMenu();
  }

  mainMenuIsOpen(): boolean {
    return this.offcanvas.isOpen;
  }

  getUserName(): string {
    return this.user.name.split(' ')[0];
  }

  getUserLastName(): string {
    const names = this.user.name.split(' ');
    return names.length > 0 ? names[names.length - 1] : null;
  }

}
