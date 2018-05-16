import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import * as CryptoJS from 'crypto-js';

@Injectable()
export class GithubService {

  private username = 'scottmccaughey';
  private githubRoot = 'https://api.github.com/repos/ombegov/ITDB-schema/';
  private githubContents = 'contents/src/';
  private githubCommits = 'commits?path=';
  private tokenCode = 'U2FsdGVkX19tdJtCz389lUqX76HeLWh6I9Sl5FheRAHsm8lnIRXPe7rO0JpBiwXOqjoTConymgkOCYRRogf1+A==';
  private salt = 'sHjvqU7bNyKZw0ArXwCB';

  constructor( private _http: HttpClient ) { }

  getToken() {
    return CryptoJS.AES.decrypt(this.tokenCode, this.salt).toString(CryptoJS.enc.Utf8);
  }

  getSchemaCats() {
    const token = this.getToken();
    const headers = new HttpHeaders();
    return this._http.get(this.githubRoot + this.githubContents, {
      headers: new HttpHeaders({'Authorization': 'token ' + token})
    });
  }

  getExamples(schemaCat) {
    const token = this.getToken();
    const exampleFormat = (schemaCat === 'InvestmentReport') ? '/examples' : '/Examples';
    return this._http.get(this.githubRoot + this.githubContents + schemaCat + exampleFormat, {
      headers: new HttpHeaders({'Authorization': 'token ' + token})
    });
  }

  getFileInfo(path) {
    const token = this.getToken();
    return this._http.get(this.githubRoot + this.githubCommits + path, {
      headers: new HttpHeaders({'Authorization': 'token ' + token})
    });
  }

}
