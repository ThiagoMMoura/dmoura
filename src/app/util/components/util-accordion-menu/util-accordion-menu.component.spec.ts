import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UtilAccordionMenuComponent } from './util-accordion-menu.component';

describe('UtilAccordionMenuComponent', () => {
  let component: UtilAccordionMenuComponent;
  let fixture: ComponentFixture<UtilAccordionMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UtilAccordionMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UtilAccordionMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
