import { Component, OnInit } from '@angular/core';
import { GithubService } from '../../data/github.service';

@Component({
  selector: 'app-code',
  templateUrl: './code.component.html',
  styleUrls: ['./code.component.scss']
})
export class CodeComponent implements OnInit {
  schemaCats$: any;
  monthNames = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];

  constructor(
    private _githubService: GithubService
  ) {}

  async getSchemas() {

    const schemaCatsObject: any = await this._githubService.getSchemaCats().toPromise();
    if (schemaCatsObject) {
      for (const schemaCat of schemaCatsObject) {
        const catExamples: any = await this._githubService.getExamples(schemaCat.name).toPromise();
        if (catExamples) {
          for (const catExample of catExamples) {
            const fileInfo: any = await this._githubService.getFileInfo(catExample.path).toPromise();
            const lastModDate = new Date(fileInfo[0].commit.author.date);
            const lastMod = this.monthNames[lastModDate.getMonth()] + ' ' + lastModDate.getDate() + ', ' + lastModDate.getFullYear();
            catExample.lastMod = lastMod;
          }
        }
        schemaCat.examples = [];
        for (const catExample of catExamples) {
          catExample.name = catExample.name.slice(0, -4);
          schemaCat.examples.push(catExample);
        }
      }
    }
    return schemaCatsObject;
  }

  ngOnInit() {
    this.schemaCats$ = this.getSchemas();
  }

}
