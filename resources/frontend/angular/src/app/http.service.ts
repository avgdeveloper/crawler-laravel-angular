import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { environment } from '../environments/environment';


@Injectable({
  providedIn: 'root'
})

export class HttpService {
  private apiUrl = environment.apiUrl;
  
  constructor(private http: HttpClient) { }

  getCsrfToken(): Observable<any> {
    return this.http.get(`${this.apiUrl}/api/csrf-token`);
  }

  submitForm(csrfToken: string, formData: any): Observable<any> {
    const headers = { 'X-CSRF-TOKEN': csrfToken };
    return this.http.post(`${this.apiUrl}/api/crawl`, formData, { headers });
  }
}