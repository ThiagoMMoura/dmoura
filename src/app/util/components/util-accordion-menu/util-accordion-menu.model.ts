export class UtilAccordionMenuModel {
    title: string;
    url: string|null;
    icon?: any|null = null;
    // tslint:disable-next-line:no-inferrable-types
    isSubmenu?: boolean = false;
    submenu?: UtilAccordionMenuModel[] = [];
    // tslint:disable-next-line:no-inferrable-types
    active?: boolean = false;
    // tslint:disable-next-line:no-inferrable-types
    expanded?: boolean = false;
}
