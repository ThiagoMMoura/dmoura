import { ComponentFixture, TestBed, waitForAsync } from '@angular/core/testing';

import { UtilPageContentWithTopBarComponent } from './util-page-content-with-top-bar.component';

describe('UtilPageContentWithTopBarComponent', () => {
  let component: UtilPageContentWithTopBarComponent;
  let fixture: ComponentFixture<UtilPageContentWithTopBarComponent>;

  beforeEach(waitForAsync(() => {
    TestBed.configureTestingModule({
      declarations: [ UtilPageContentWithTopBarComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UtilPageContentWithTopBarComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
