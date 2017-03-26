'use strict';

describe('App tests', function() {

  it('List app', function() {
    browser.get('#/app');

    var routes = element.all(by.repeater('app in apps'));
    expect(routes.count()).toEqual(4);
    expect(routes.get(0).getText()).toMatch('Pending');
    expect(routes.get(1).getText()).toMatch('Foo-App');
    expect(routes.get(2).getText()).toMatch('Consumer');
    expect(routes.get(3).getText()).toMatch('Backend');
  });

  it('Create app', function() {
    browser.get('#/app');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('state.key as state.value for state in states'));
    expect(statusOptions.get(0).getText()).toEqual('Active');
    expect(statusOptions.get(1).getText()).toEqual('Pending');
    expect(statusOptions.get(2).getText()).toEqual('Deactivated');
    statusOptions.get(0).click();

    var userOptions = element.all(by.options('user.id as user.name for user in users'));
    expect(userOptions.get(0).getText()).toEqual('');
    expect(userOptions.get(1).getText()).toEqual('Developer');
    expect(userOptions.get(2).getText()).toEqual('Disabled');
    expect(userOptions.get(3).getText()).toEqual('Consumer');
    expect(userOptions.get(4).getText()).toEqual('Administrator');
    userOptions.get(1).click();

    element(by.model('app.name')).sendKeys('test-app');
    element(by.model('app.url')).sendKeys('http://foo.com');
    element(by.model('app.parameters')).sendKeys('foo=bar&bar=1');

    element.all(by.model('app.scopes[$index]')).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('App successful created');
  });

  it('Update app', function() {
    browser.get('#/app');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('app.status')).getAttribute('value')).toEqual('number:1');
    expect(element(by.model('app.name')).getAttribute('value')).toEqual('test-app');
    expect(element(by.model('app.url')).getAttribute('value')).toEqual('http://foo.com');
    expect(element(by.model('app.parameters')).getAttribute('value')).toEqual('foo=bar&bar=1');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('App successful updated');
  });

  it('Delete app', function() {
    browser.get('#/app');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('app.name')).getAttribute('value')).toEqual('test-app');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('App successful deleted');
  });

});
