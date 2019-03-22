import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UtilTopBarComponent } from './util-top-bar.component';

describe('UtilTopBarComponent', () => {
  let component: UtilTopBarComponent;
  let fixture: ComponentFixture<UtilTopBarComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UtilTopBarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UtilTopBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
