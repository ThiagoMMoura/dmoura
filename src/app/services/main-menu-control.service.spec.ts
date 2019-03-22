import { TestBed } from '@angular/core/testing';

import { MainMenuControlService } from './main-menu-control.service';

describe('MainMenuControlService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: MainMenuControlService = TestBed.get(MainMenuControlService);
    expect(service).toBeTruthy();
  });
});
