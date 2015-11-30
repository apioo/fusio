
describe('Import tests', function() {

  it('List import', function() {
    browser.get('#/import');

    var raml = '';

    element(by.model('source')).sendKeys(raml);

    $('button.btn-default').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    $('button.btn-default').click();


  });

});
