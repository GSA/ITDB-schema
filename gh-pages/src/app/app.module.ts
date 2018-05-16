import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { HttpModule } from '@angular/http';
import { RouterModule, Routes } from '@angular/router';
import { FormsModule } from '@angular/forms';

import { MarkdownToHtmlModule } from 'ng2-markdown-to-html';
import { UiSwitchModule } from 'ngx-ui-switch';

import { DataModule } from './modules/data/data.module';
import { ListModule } from './modules/list/list.module';
import { CoreModule } from './modules/core/core.module';
import { JiraModule } from './modules/jira/jira.module';

import { AppComponent } from './app.component';

import { IntroComponent } from './modules/core/intro/intro.component';
import { ScheduleComponent } from './modules/list/schedule/schedule.component';
import { IssueComponent } from './modules/list/issue/issue.component';
import { CodeComponent } from './modules/list/code/code.component';
import { SchemaComponent } from './modules/list/code/schema/schema.component';
import { ValidationComponent } from './modules/list/validation/validation.component';
import { TicketComponent } from './modules/jira/ticket/ticket.component';
import { AddendumsComponent } from './modules/core/addendums/addendums.component';

const routes: Routes = [
  {
    path: '',
    redirectTo: 'intro',
    pathMatch: 'full'
  },
  {
    path: 'intro',
    component: IntroComponent
  },
  {
    path: 'schedule',
    component: ScheduleComponent
  },
  {
    path: 'issues',
    redirectTo: 'issues/open',
    pathMatch: 'full'
  },
  {
    path: 'issues/:state',
    pathMatch: 'full',
    component: IssueComponent
  },
  {
    path: 'schema',
    pathMatch: 'full',
    component: CodeComponent
  },
  {
    path: 'schema/:schemaCat',
    pathMatch: 'full',
    component: CodeComponent
  },
  {
    path: 'schema/:schemaCat/:schema',
    component: SchemaComponent
  },
  {
    path: 'validations',
    component: ValidationComponent
  },
  {
    path: 'jira-board',
    component: TicketComponent
  },
  {
    path: 'addendums',
    component: AddendumsComponent
  },
  {
    path: '**',
    redirectTo: 'intro'
  }
];

@NgModule({
  declarations: [
    AppComponent,
    SchemaComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    DataModule,
    ListModule,
    CoreModule,
    JiraModule,
    HttpModule,
    RouterModule,
    MarkdownToHtmlModule.forRoot(),
    UiSwitchModule,
    RouterModule.forRoot(routes, { useHash: true })
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
