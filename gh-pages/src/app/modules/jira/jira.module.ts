import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { TicketComponent } from './ticket/ticket.component';

import { MarkdownToHtmlModule } from 'ng2-markdown-to-html';

@NgModule({
  imports: [
    CommonModule,
    MarkdownToHtmlModule.forRoot()
  ],
  declarations: [TicketComponent],
  exports: [TicketComponent]
})
export class JiraModule { }
