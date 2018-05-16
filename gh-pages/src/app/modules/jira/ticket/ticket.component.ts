import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../data/api.service';

@Component({
  selector: 'app-ticket',
  templateUrl: './ticket.component.html',
  styleUrls: ['./ticket.component.scss']
})
export class TicketComponent implements OnInit {
  isOpen = {};
  public tickets;
  monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  constructor(private _api: ApiService) { }

  public ngOnInit() {

    this._api.loadData('./assets/data/egovjira.json').subscribe((results) => {
      const json = results;
      for (let i = 0; i < json.length; i++) {
        const created = new Date(json[i]['Created']);
        json[i]['Created'] = this.monthNames[created.getMonth()] + ' ' + created.getDate() + ', ' + created.getFullYear();

        const updated = new Date(json[i]['Updated']);
        json[i]['Updated'] = this.monthNames[updated.getMonth()] + ' ' + updated.getDate() + ', ' + updated.getFullYear();

        let description = json[i]['Description'];
        description = description.replace(/\uFFFD/g, '');
        description = description.replace(/\{panel\}/g, '');
        json[i]['Description'] = description;
      }
      this.tickets = json;
    });
  }

}
