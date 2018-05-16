import { Component, OnInit } from '@angular/core';
import { Http } from '@angular/http';

@Component({
  selector: 'app-schedule',
  templateUrl: './schedule.component.html',
  styleUrls: ['./schedule.component.scss']
})
export class ScheduleComponent implements OnInit {
  schedule: any = '';
  isOpen = {};

  constructor(http: Http) {
    http.get('https://raw.githubusercontent.com/ombegov/ITDB-schema/master/README.md').subscribe(data => {
      let md = data.text();
      let split = md.split('## Vendor Meetings');
      md = '## Vendor Meetings' + split[1];
      split = md.split('## Quick Links to Files');
      this.schedule = split[0];
    });
  }

  ngOnInit() {
  }

}
