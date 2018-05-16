import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AddendumsComponent } from './addendums.component';

describe('AddendumsComponent', () => {
  let component: AddendumsComponent;
  let fixture: ComponentFixture<AddendumsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AddendumsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AddendumsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
