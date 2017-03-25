'use strict';

describe('Token tests', function() {

  it('List token', function() {
    browser.get('#/token');

    var errors = element.all(by.repeater('token in tokens').column('token.scope'));
    expect(errors.count()).toEqual(4);
    expect(errors.get(0).getText()).toMatch('backend authorization');
  });

  it('Detail token', function() {
    browser.get('#/token');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).get(0).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('token.app.name')).getText()).toEqual('');
    expect(element(by.model('token.user.name')).getText()).toEqual('');
    expect(element(by.model('token.status')).getText()).toEqual('');
    expect(element(by.model('token.scope')).getText()).toEqual('');
    expect(element(by.model('token.ip')).getText()).toEqual('');

    $('button.btn-default').click();
  });

});
