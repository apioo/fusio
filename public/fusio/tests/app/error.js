'use strict';

describe('Error tests', function() {

  it('List error', function() {
    browser.get('#/error');

    var errors = element.all(by.repeater('error in errors').column('error.message'));
    expect(errors.count()).toEqual(1);
    expect(errors.get(0).getText()).toMatch('Syntax error, malformed JSON');
  });

  it('Detail error', function() {
    browser.get('#/error');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).get(0).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.css('.fusio-error-detail-message')).getText()).toEqual('Syntax error, malformed JSON');
    expect(element(by.css('.fusio-error-detail-file')).getText()).toEqual('[file]');
    expect(element(by.css('.fusio-error-detail-line')).getText()).toEqual('74');
    expect(element(by.css('.fusio-error-detail-trace')).getText()).toEqual('[trace]');

    $('button.btn-default').click();
  });

});
