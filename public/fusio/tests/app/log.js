'use strict';

describe('Log tests', function() {

  it('List log', function() {
    browser.get('#/log');

    var logs = element.all(by.repeater('log in logs').column('log.path'));
    expect(logs.count()).toEqual(2);
    expect(logs.get(0).getText()).toMatch('/bar');
    expect(logs.get(1).getText()).toMatch('/bar');
  });

  it('Detail log', function() {
    browser.get('#/log');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).get(1).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.css('.fusio-log-detail-header')).getText()).toEqual('Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8');
    expect(element(by.css('.fusio-log-detail-body')).getText()).toEqual('foobar');

    var errors = element.all(by.repeater('error in log.errors').column('error.message'));
    expect(errors.count()).toEqual(1);
    expect(errors.get(0).getText()).toMatch('Syntax error, malformed JSON');

    $('button.btn-default').click();
  });

});
