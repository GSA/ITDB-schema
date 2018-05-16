import { Component, OnInit } from '@angular/core';
import { Http } from '@angular/http';

@Component({
  selector: 'app-intro',
  templateUrl: './intro.component.html',
  styleUrls: ['./intro.component.scss']
})
export class IntroComponent implements OnInit {
  intro: any = '';

  constructor(http: Http) {
    http.get('https://raw.githubusercontent.com/scottmccaughey/ITDB-schema/master/gh-pages/src/assets/content/intro.md').subscribe(data => {
      this.intro = data.text();
    });
  }

  ngOnInit() {
  }

}
