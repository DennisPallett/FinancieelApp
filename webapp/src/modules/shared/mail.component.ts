import { Component, Input, Output, EventEmitter } from '@angular/core';

@Component({
  selector: 'app-mail',
  template: '<a (click)="clicked()" href="mailto:{{email}}">{{email}}</a>'
})

export class MailComponent {
  @Input() email: string;
  @Output() emailClicked: EventEmitter<string> = new EventEmitter();

  clicked() {
    this.emailClicked.emit(this.email + ' Clicked!');
  }
}
