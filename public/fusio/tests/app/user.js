'use strict';

describe('User tests', function() {

  it('List user', function() {
    browser.get('#/user');

    var users = element.all(by.repeater('user in users'));
    expect(users.count()).toEqual(4);
    expect(users.get(0).getText()).toMatch('Developer');
    expect(users.get(1).getText()).toMatch('Disabled');
    expect(users.get(2).getText()).toMatch('Consumer');
    expect(users.get(3).getText()).toMatch('Administrator');
  });

  it('Create user', function() {
    browser.get('#/user');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('status.id as status.name for status in statuuus'));
    expect(statusOptions.get(0).getText()).toEqual('Consumer');
    expect(statusOptions.get(1).getText()).toEqual('Administrator');
    expect(statusOptions.get(2).getText()).toEqual('Disabled');

    statusOptions.get(1).click();

    element(by.model('user.name')).sendKeys('test-user');
    element(by.model('user.email')).sendKeys('foo@bar.com');
    element(by.model('user.password')).sendKeys('test1234!');
    element.all(by.model('user.scopes[$index]')).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toMatch('User successful created');
  });

  it('Update user', function() {
    browser.get('#/user');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('user.status')).getAttribute('value')).toEqual('number:1');
    expect(element(by.model('user.name')).getAttribute('value')).toEqual('test-user');

    var scopes = element.all(by.model('user.scopes[$index]'));

    expect(scopes.get(0).getAttribute('checked')).toBeTruthy();
    expect(scopes.get(1).getAttribute('checked')).toBeTruthy();
    expect(scopes.get(2).getAttribute('checked')).toBeTruthy();
    expect(scopes.get(3).getAttribute('checked')).toBeTruthy();

    // we remove all selected scopes so that we can delete the user
    element.all(by.model('user.scopes[$index]')).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('User successful updated');
  });

  it('Delete user', function() {
    browser.get('#/user');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('user.name')).getAttribute('value')).toEqual('test-user');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('User successful deleted');
  });

});
