import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UtilTopBarToggleButtonComponent } from './util-top-bar-toggle-button.component';

describe('UtilTopBarToggleButtonComponent', () => {
  let component: UtilTopBarToggleButtonComponent;
  let fixture: ComponentFixture<UtilTopBarToggleButtonComponent>;

  beforeEach(waitForAsync(() => {
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
