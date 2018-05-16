import { Component, OnInit, Input } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { UiSwitchModule } from 'ngx-ui-switch';
import { ApiService } from '../../data/api.service';

@Component({
  selector: 'app-issue',
  templateUrl: './issue.component.html',
  styleUrls: ['./issue.component.scss']
})
export class IssueComponent implements OnInit {
  isOpen = {};
  public issues;
  url = 'https://api.github.com/repos/ombegov/ITDB-schema/issues?state=';
  state;
  switchState;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private _api: ApiService
  ) { }

  switchChange() {
    this.switchState = !this.switchState;
    this.state = this.switchState ? 'open' : 'closed';
    this.router.navigate(['../' + this.state], { relativeTo: this.route });
    this.loadIssues();
  }

  loadIssues() {
    const monthNames = [
      'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
    ];

    this._api.loadData(this.url + this.state).subscribe((results) => {
      const json = results;
      for (let i = 0; i < json.length; i++) {
        const created = new Date(json[i]['created_at']);
        json[i]['created_at'] = monthNames[created.getMonth()] + ' ' + created.getDate() + ', ' + created.getFullYear();

        const updated = new Date(json[i]['updated_at']);
        json[i]['updated_at'] = monthNames[updated.getMonth()] + ' ' + updated.getDate() + ', ' + updated.getFullYear();
      }
      this.issues = json;
    });
  }

  public ngOnInit() {
    this.route.params.subscribe(params => {
      this.state = params['state'] ? params['state'] : 'open';
      this.switchState = (this.state === 'open') ? true : false;
    });

    this.loadIssues();
  }
}
