import { Component, OnInit } from '@angular/core';
import { ApiService } from '../../data/api.service';

@Component({
  selector: 'app-validation',
  templateUrl: './validation.component.html',
  styleUrls: ['./validation.component.scss']
})
export class ValidationComponent implements OnInit {

  isOpen = {};
  public validations;
  public filter;
  public statuses = [];
  public statusFilter;
  public sections = [];
  public sectionFilter;
  public validationTypes = [];
  public validationTypeFilter;

  constructor(private _api: ApiService) { }

  titleCase(str) {
    return str.toLowerCase().split(' ').map(function(word) {
      return word.replace(word[0], word[0].toUpperCase());
    }).join(' ');
  }

  public ngOnInit() {
    this._api.loadData('./assets/data/validations.json').subscribe((results) => {
      const itp = results['ITP Valids'];
      const bc = results['BC Valids'];
      const bcd = results['BCD Valids'];
      const vals = itp.concat(bc, bcd);

      for (let i = 0; i < vals.length; i++) {
        for (const key of Object.keys(vals[i])) {
          switch (key) {
            case 'Status':
              const status = '<status>' + this.titleCase(vals[i][key]) + '</status>';
              vals[i][key] = status;
              this.statuses.push(status);
              break;
            case 'Section':
              const sectionIT =
                (this.titleCase(vals[i][key]) === 'It Portfolio Summary') ? 'IT Portfolio Summary' : this.titleCase(vals[i][key]);
              const section = '<sectionName>' + sectionIT + '</sectionName>';
              vals[i][key] = section;
              this.sections.push(section);
              break;
            case 'Validation Type':
              const validationType = '<validationType>' + this.titleCase(vals[i][key]) + '</validationType>';
              vals[i][key] = validationType;
              this.validationTypes.push(validationType);
              break;
          }
        }
      }

      this.statusFilter = '';
      this.sectionFilter = '';
      this.validationTypeFilter = '';
      this.statuses = Array.from(new Set(this.statuses)).sort();
      this.sections = Array.from(new Set(this.sections)).sort();
      this.validationTypes = Array.from(new Set(this.validationTypes)).sort();
      this.validations = vals;
    });

  }
}
