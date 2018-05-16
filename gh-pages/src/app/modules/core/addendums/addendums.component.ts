import { Component, OnInit } from '@angular/core';
import { Http } from '@angular/http';

@Component({
  selector: 'app-addendums',
  templateUrl: './addendums.component.html',
  styleUrls: ['./addendums.component.scss']
})
export class AddendumsComponent implements OnInit {
  addendums: any = '';

  constructor(http: Http) {
    http.get(
      'https://raw.githubusercontent.com/scottmccaughey/ITDB-schema/master/gh-pages/src/assets/content/guidance-addendums.md'
    ).subscribe(data => {
      this.addendums = data.text();
    });
  }

  ngOnInit() {
  }

}
