export class UtilAccordionMenuModel {
    title: string;
    url: string|null;
    icon: any|null;
    isSubmenu: boolean;
    submenu: UtilAccordionMenuModel[];
    active: boolean;
    expanded: boolean;

    constructor(title: string, url: string,
                icon: any = null,
                isSubmenu: boolean = false,
                submenu: UtilAccordionMenuModel[] = [],
                active: boolean = false,
                expanded: boolean = false) {
        this.title = title;
        this.url = url;
        this.icon = icon;
        this.isSubmenu = isSubmenu;
        this.submenu = submenu;
        this.active = active;
        this.expanded = expanded;
    }

    hasSubUrl(url: string) {
        if (this.isSubmenu) {
            for (const menu of this.submenu) {
                if (menu.url === url) {
                    return true;
                }
            }
        }
        return false;
    }
}
