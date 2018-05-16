import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'cpic-mainnav',
  templateUrl: './mainnav.component.html',
  styleUrls: ['./mainnav.component.scss']
})
export class MainnavComponent implements OnInit {
  navOpen = false;

  constructor() { }

  ngOnInit() {
  }

}
