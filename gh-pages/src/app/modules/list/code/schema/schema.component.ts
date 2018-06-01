import { Component, Input, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Http } from '@angular/http';

@Component({
  selector: 'app-schema',
  templateUrl: './schema.component.html',
  styleUrls: ['./schema.component.scss']
})
export class SchemaComponent implements OnInit {
  content: any = '';
  schemaCat;
  schema;
  urlRoot = 'https://rawgit.com/ombegov/ITDB-schema/master/src/';
  exName;

  constructor(
    private route: ActivatedRoute,
    private http: Http
  ) {}

  ngOnInit() {
    this.route.params.subscribe(params => {
      this.schemaCat = params['schemaCat'];
      this.schema = params['schema'];
    });

    // this.exName = (this.schemaCat === 'InvestmentReport') ? '/examples/' : '/Examples/';
    // this.exName = (this.schemaCat === 'ContractsReport') ? '/examples/' : '/Examples/';

    this.exName  = (['InvestmentReport','ContractsReport','SystemsInventory'].includes(this.schemaCat)) ? '/examples/' : '/Examples/';    

    this.http.get(this.urlRoot + this.schemaCat + this.exName + this.schema + '.xml').subscribe(data => {
      const text = data.text().replace(/\n$/, '');
      this.content = text;
    });
  }

}
