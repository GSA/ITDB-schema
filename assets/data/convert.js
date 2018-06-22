'use strict';

const excelToJson = require('../../../node_modules/convert-excel-to-json');
const fs = require('fs');

const result = excelToJson({
  sourceFile: 'validations.xlsx',
  sheets: ['ITP Valids', 'BC Valids', 'BCD Valids'],
  header: {
    rows: 1
  },
  columnToKey: {
    A: 'UI',
    B: 'Status',
    C: 'Section',
    D: 'Sub-Section',
    E: 'FY19 Field',
    F: 'Loc in Data Dictionary',
    G: 'Validation Type',
    H: 'Validation Description',
    I: 'Comments'
  }
});

const json = JSON.stringify(result);

fs.writeFile('validations.json', json, function(err) {
  if(err) {
    return console.log(err);
  }
  console.log('The validations file was saved!');
});

const resultdd = excelToJson({
  sourceFile: 'by20datadictionary.xlsx',
  sheets: ['Guidance Data Dictionary'],
  header: {
    rows: 1
  },
  columnToKey: {
    A: 'XML Elements',
    B: 'Field Requirement',
    C: 'Database Type',
    D: 'Example',
    E: 'Description',
    F: 'Guidance Section',
    G: 'Guidance Subsection',
    H: 'Change in FY20',
    I: 'BA Comment',
    J: 'Status'
  }
});

const jsondd = JSON.stringify(resultdd);

fs.writeFile('datadictionary.json', jsondd, function(err) {
  if(err) {
    return console.log(err);
  }
  console.log('The data dictionary file was saved!');
});
