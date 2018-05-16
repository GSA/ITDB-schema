import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';

import { ApiService } from './api.service';
import { SearchService } from './search.service';
import { GithubService } from './github.service';

import { MarkdownToHtmlModule } from 'ng2-markdown-to-html';

@NgModule({
  imports: [
    CommonModule,
    MarkdownToHtmlModule.forRoot()
  ],
  providers: [
    ApiService,
    SearchService,
    GithubService
  ]
})
export class DataModule { }
