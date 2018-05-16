import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { HttpClientModule, HttpClient } from '@angular/common/http';
import { FormsModule } from '@angular/forms';
import { DataModule } from '../data/data.module';
import { IssueComponent } from './issue/issue.component';
import { ScheduleComponent } from './schedule/schedule.component';
import { ValidationComponent } from './validation/validation.component';
import { DictionaryComponent } from './dictionary/dictionary.component';
import { NavComponent } from './validation/nav/nav.component';
import { CodeComponent } from './code/code.component';
import { Ng2SearchPipeModule } from 'ng2-search-filter';
import { FilterPipeModule } from 'ngx-filter-pipe';
import { MarkdownToHtmlModule } from 'ng2-markdown-to-html';
import { UiSwitchModule } from 'ngx-ui-switch';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    HttpClientModule,
    DataModule,
    Ng2SearchPipeModule,
    FilterPipeModule,
    MarkdownToHtmlModule.forRoot(),
    UiSwitchModule
  ],
  declarations: [IssueComponent, ScheduleComponent, ValidationComponent, DictionaryComponent, NavComponent, CodeComponent],
  exports: [IssueComponent, ScheduleComponent, ValidationComponent, DictionaryComponent, NavComponent, CodeComponent]
})
export class ListModule { }
