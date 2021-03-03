import { Injectable } from '@angular/core';
import pdfMake from 'pdfmake/build/pdfmake';
import pdfFonts from 'pdfmake/build/vfs_fonts';
pdfMake.vfs = pdfFonts.pdfMake.vfs;
import { CanvasService } from './canvas.service';

@Injectable({
  providedIn: 'root'
})
export class PdfService {

  constructor(private canvas: CanvasService) { }

  /** Method to set PDF Title */
  setTitle(logo: any, title: string, page: any, pageSize: string): any {
    // let companyDetail = '';
    let margin;
    let fontSize;

    if (pageSize === 'A6') {
      margin = [15, 10, 0, 10];
      fontSize = 8;
    } else {
      margin = [15, 10, 0, 10];
      fontSize = 10;
    }

    return [
      {
        table: {
          widths: ['35%', '65%'],
          body: [
            [{
              image: logo,
              width: 60,
              height: 30,
            }],
            [{ text: title, fontSize, margin: [100, -20, 0, 0] }],
          ],
        },
        layout: 'noBorders',
      },

    ];
  }

  /**  Method for creating PDF table header */
  createTableHeader(header: string[]): any {
    const pageHeader = { fila_0: {} };
    header.forEach((attribute, i) => {
      pageHeader.fila_0['col_' + (+i + 1)] = { text: attribute, style: 'tableHeader', margin: [0, 8, 0, 0] };
    });
    return pageHeader;
  }

  /** Method for creating PDF table content */
  createBody(headers: object, records: object[]): any {
    const body = [];
    // tslint:disable-next-line:forin
    for (const key in headers) {
      const row = [];
      // tslint:disable-next-line: forin
      for (const headerKey in headers[key]) {
        row.push(headers[key][headerKey]);
      }
      body.push(row);
    }

    records.forEach((record) => {
      const row = [];
      // tslint:disable-next-line: forin
      for (const key in record) {
        row.push(record[key]);
      }
      body.push(row);
    });
    return body;
  }

  /** Define PDF document definition */
  // tslint:disable-next-line: max-line-length
  getDocDefinition(title: string, logo: any, header: string[],
    record: object[], orientation: string, columns: string[], pageSize?: string): any {

    const page = {
      header: title,
      body: { header, record },
    };
    const body = this.createBody(this.createTableHeader(page.body.header), page.body.record);

    let pageMargin;
    let headerFont;
    let contentFont;
    if (pageSize === 'A6') {
      pageMargin = [10, 110, 10, 55];
      headerFont = 10;
      contentFont = 7;
    } else {
      headerFont = 16;
      contentFont = 10;
      pageMargin = [10, 110, 10, 55];
    }
    const docDefinition = {
      pageOrientation: orientation,
      pageSize,
      pageMargins: pageMargin,
      header: this.setTitle(logo, title, page, pageSize),
      footer: (currentPage, pageCount) => {
        return { text: 'Page ' + currentPage.toString() + ' of ' + pageCount, alignment: 'center', margin: [0, 30, 0, 0] };
      },

      content: [
        {
          margin: [10, -40, 10, 10],
          style: 'tableContent',
          table: {
            widths: columns,
            headerRows: 1,
            body,
          },
        },
      ],
      styles: {
        header: {
          fontSize: headerFont,
          bold: true,
        },
        tableHeader: {
          bold: true,
        },
        tableContent: {
          fontSize: contentFont,
        },
      },
    };
    return docDefinition;
  }

  generatePdf(docDefinition): void {
    const pdfObject = pdfMake.createPdf(docDefinition);
    // On a browser simply use download!
    pdfObject.download();
  }

  getPdfData(data: any[]): void {
    const columns = ['40%', '15%', '45%'];
    const header = ['Prenom', 'Nom', 'Email'];


    const title = 'Student Information';
    // tslint:disable-next-line: max-line-length
    this.canvas.getBase64Image('../assets/images/loginPage.png')
      .then(base64Img => {
        const logo = base64Img;
        this.generatePdf(this.getDocDefinition(title, logo, header, this.mapData(data), 'portrait', columns, 'A4'));
      });

  }

  // tslint:disable-next-line: typedef
  mapData(data: any[]): any {
    return data.map((item) => {
      return {
        Prenom: item.prenom,
        Nom: item.nom,
        Email: item.email
      };
    });
  }
}

