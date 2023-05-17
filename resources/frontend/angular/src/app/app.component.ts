import { Component } from '@angular/core';
import { FormBuilder, Validators } from '@angular/forms';
import { HttpErrorResponse } from '@angular/common/http';
import { HttpService } from './http.service';



@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})

export class AppComponent {
  title = 'Site Crawler';
  urls: any[] = [];
  message: string = '';
  errors: any = '';


  form = this.fb.group({
    url: ['', Validators.required],
    depth: ['', [Validators.required]],
    update: ['', []]
  });

  csrfToken: string = '';

  constructor(private fb: FormBuilder, private httpService: HttpService) {}


  ngOnInit() {
    this.httpService.getCsrfToken().subscribe((response: any) => {
      this.csrfToken = response.csrfToken;
    });
  }


  onSubmit() {
    this.urls = [];
    this.errors = '';
    this.message = 'Processing..';
    
    this.httpService.submitForm(this.csrfToken, this.form.value).subscribe(
      (response: any )=> {
        if(response.error) {
          this.errors = {'error': [response.error]};
        } else {
          this.message = response.message ? response.message : '';
          this.urls = response.urls ? response.urls : [];
        }
       },
       (error: HttpErrorResponse) => {
          this.errors = error.error;
        }
    );
  }

  objectKeys(obj: any) {
    return Object.keys(obj);
  }

}
