import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UtilTopBarToggleButtonComponent } from './util-top-bar-toggle-button.component';

describe('UtilTopBarToggleButtonComponent', () => {
  let component: UtilTopBarToggleButtonComponent;
  let fixture: ComponentFixture<UtilTopBarToggleButtonComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UtilTopBarToggleButtonComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UtilTopBarToggleButtonComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
