import { Observable } from 'rxjs/Observable';
import { Injectable } from '@angular/core';
import { Http, Response } from '@angular/http';
import 'rxjs/add/operator/map';

@Injectable()
export class ApiService {


  // https://jsonplaceholder.typicode.com/posts

  constructor(
          public http: Http
      ) {}

  obj = {
    id: 12,
    name: 'asdf',
    rollno: 98
  };

  success() {
    return true;
  }

  loadData(url) {
    return this.http.get(url).map((res: Response) => res.json());
  }

  public fetchUsers() {
      return this.http.get('https://jsonplaceholder.typicode.com/posts').map((res: Response) => res.json());
  }

}
