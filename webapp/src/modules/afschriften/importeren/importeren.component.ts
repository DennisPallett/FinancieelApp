import { Component, OnInit } from '@angular/core';
import { AfschriftenService } from '../afschriften.service';
import { IImportResult } from '../importresult.model';

@Component({
  templateUrl: './importeren.component.html'
})
export class ImporterenComponent implements OnInit {
  selectedFiles: FileList = null;

  importInProgress: boolean = false;

  importResult: IImportResult[] = null;

  importError: string = null;

  importErrorCode: number = null;

  constructor(
    private afschriftenService: AfschriftenService) {
  }

  ngOnInit() {
    this.importInProgress = false;
    this.importResult = null;
  }

  handleFileInput(files: FileList) {
    this.selectedFiles = files;
  }

  startImport() {
    if (this.selectedFiles.length == 0) return false;

    this.importInProgress = true;
    this.importError = null;
    this.importErrorCode = null;

    this.afschriftenService.importFiles(this.selectedFiles).subscribe((result) => {
      this.importInProgress = false;
      this.importResult = result;
    }, (error) => {
      this.importInProgress = false;
      this.importError = error.error;
      this.importErrorCode = error.status;
    });
  }

}
