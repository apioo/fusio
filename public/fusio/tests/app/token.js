'use strict';

describe('Token tests', function() {

  it('List token', function() {
    browser.get('#/token');

    var tokens = element.all(by.repeater('token in tokens').column('scope'));
    expect(tokens.count()).toEqual(2);
    expect(tokens.get(0).getText()).toMatch('backend');
    expect(tokens.get(1).getText()).toMatch('authorization');
  });

  it('Detail token', function() {
    browser.get('#/token');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).get(0).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('token.app.name')).getAttribute('value')).toEqual('Backend');
    expect(element(by.model('token.user.name')).getAttribute('value')).toEqual('Developer');
    expect(element(by.model('token.status')).getAttribute('value')).toEqual('number:1');
    expect(element(by.model('token.scope')).getAttribute('value')).toEqual('backend,authorization');
    expect(element(by.model('token.ip')).getAttribute('value')).toEqual('127.0.0.1');

    $('button.btn-default').click();
  });

});
