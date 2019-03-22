import { Component, OnInit, Input } from '@angular/core';

@Component({
  selector: 'app-util-top-bar',
  templateUrl: './util-top-bar.component.html',
  styleUrls: ['./util-top-bar.component.scss']
})
export class UtilTopBarComponent implements OnInit {
  @Input()
  title: string;

  constructor() { }

  ngOnInit() {
  }

}
