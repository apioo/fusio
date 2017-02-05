'use strict';

describe('Schema tests', function() {

  it('List schema', function() {
    browser.get('#/schema');

    var schemas = element.all(by.repeater('schema in schemas'));
    expect(schemas.count()).toEqual(2);
    expect(schemas.get(0).getText()).toEqual('Foo-Schema');
    expect(schemas.get(1).getText()).toEqual('Passthru');
  });

  it('Create schema', function() {
    browser.get('#/schema');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('schema.name')).sendKeys('test-schema');
    element(by.css('textarea.ace_text-input')).sendKeys('{ "id": "http://acme.com/schema", "type": "object", "title": "schema", "properties": { "name": { "type": "string" }, "date": { "type": "string", "format": "date-time" } } }');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Schema successful created');
  });

  it('Update schema', function() {
    browser.get('#/schema');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('schema.name')).getAttribute('value')).toEqual('test-schema');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Schema successful updated');
  });

  it('Delete schema', function() {
    browser.get('#/schema');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('schema.name')).getAttribute('value')).toEqual('test-schema');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Schema successful deleted');
  });

});
