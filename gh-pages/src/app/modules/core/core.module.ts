import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { MenuComponent } from './menu/menu.component';
import { FooterComponent } from './footer/footer.component';
import { MainnavComponent } from './mainnav/mainnav.component';
import { RouterModule, Routes } from '@angular/router';

import { MarkdownToHtmlModule } from 'ng2-markdown-to-html';

import { IntroComponent } from './intro/intro.component';
import { AddendumsComponent } from './addendums/addendums.component';
import { ScheduleComponent } from '../list/schedule/schedule.component';
import { IssueComponent } from '../list/issue/issue.component';
import { CodeComponent } from '../list/code/code.component';
import { ValidationComponent } from '../list/validation/validation.component';
import { TicketComponent } from '../jira/ticket/ticket.component';

@NgModule({
  imports: [
    CommonModule,
    RouterModule,
    MarkdownToHtmlModule.forRoot()
  ],
  declarations: [MenuComponent, FooterComponent, MainnavComponent, IntroComponent, AddendumsComponent],
  exports: [MenuComponent, FooterComponent, MainnavComponent, IntroComponent, AddendumsComponent]

})
export class CoreModule { }
