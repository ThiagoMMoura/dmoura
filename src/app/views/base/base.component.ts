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
  private offcanvas = { isOpen: false };
  private mainMenuCtrlServiceSubscription: Subscription;

  user = { name: 'Web Master' , imgUrl: ''};

  mainMenu: UtilAccordionMenuModel[] = [
    new UtilAccordionMenuModel('Painel de instrumentos', '/dashboard', 'dashboard'),
    new UtilAccordionMenuModel('Configuração contatos', null, 'contacts', true, [
      new UtilAccordionMenuModel('Nova Operadora', '/configuracoes-contatos/nova-operadora', 'no'),
      new UtilAccordionMenuModel('Consulta Operadora', 'consulta-operadora', 'co')
    ]),
    new UtilAccordionMenuModel('Clientes', null, 'people', true, [
      new UtilAccordionMenuModel('Novo Cliente Juridico', 'add-pessoa-juridica', 'nc'),
      new UtilAccordionMenuModel('Consulta Cliente', '', 'cc')
    ]),
    new UtilAccordionMenuModel('Segurança', '/seguranca', 'security')
  ];

  constructor(private mainMenuCtrlService: MainMenuControlService) {
    this.mainMenuCtrlServiceSubscription = mainMenuCtrlService.menuOpened$.subscribe(
      isOpened => {
        this.offcanvas.isOpen = isOpened;
      }
    );
  }

  ngOnInit() {
    this.mainMenuCtrlService.openMenu();
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
