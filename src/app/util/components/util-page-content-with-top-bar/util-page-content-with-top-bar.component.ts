import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-util-page-content-with-top-bar',
  templateUrl: './util-page-content-with-top-bar.component.html',
  styleUrls: ['./util-page-content-with-top-bar.component.scss']
})
export class UtilPageContentWithTopBarComponent implements OnInit {
  @Input()
  title: string;

  constructor() { }

  ngOnInit() {
  }

}
