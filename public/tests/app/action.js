'use strict';

describe('Action tests', function() {

  it('List action', function() {
    browser.get('#/action');

    var actions = element.all(by.repeater('action in actions'));
    expect(actions.count()).toEqual(3);
    expect(actions.get(0).getText()).toEqual('Sql-Fetch-Row');
    expect(actions.get(1).getText()).toEqual('Sql-Fetch-All');
    expect(actions.get(2).getText()).toEqual('Welcome');
  });

  it('Create action', function() {
    browser.get('#/action');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('action.name')).sendKeys('test-action');

    var actionOptions = element.all(by.options('action.class as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('HTTP-Proxy');
    expect(actionOptions.get(1).getText()).toEqual('HTTP-Request');
    expect(actionOptions.get(2).getText()).toEqual('SQL-Builder');
    expect(actionOptions.get(3).getText()).toEqual('SQL-Execute');
    expect(actionOptions.get(4).getText()).toEqual('SQL-Fetch-All');
    expect(actionOptions.get(5).getText()).toEqual('SQL-Fetch-Row');
    expect(actionOptions.get(6).getText()).toEqual('SQL-Table');
    expect(actionOptions.get(7).getText()).toEqual('Util-Cache');
    expect(actionOptions.get(8).getText()).toEqual('Util-Composite');
    expect(actionOptions.get(9).getText()).toEqual('Util-Condition');
    expect(actionOptions.get(10).getText()).toEqual('Util-Pipe');
    expect(actionOptions.get(11).getText()).toEqual('Util-Processor');
    expect(actionOptions.get(12).getText()).toEqual('Util-Static-Response');
    expect(actionOptions.get(13).getText()).toEqual('Util-Transform');
    expect(actionOptions.get(14).getText()).toEqual('Util-Try-Catch');
    expect(actionOptions.get(15).getText()).toEqual('Util-Validator');

    actionOptions.get(12).click();

    browser.wait(EC.visibilityOf($('#config-statusCode')), 5000);

    element(by.cssContainingText('#config-statusCode option', 'OK')).click();
    element(by.css('textarea.ace_text-input')).sendKeys('{"foo": "bar"}');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Action successful created');
  });

  it('Update action', function() {
    browser.get('#/action');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('action.name')).getAttribute('value')).toEqual('test-action');
    expect(element(by.model('action.class')).getAttribute('value')).toEqual('Fusio\\Adapter\\Util\\Action\\UtilStaticResponse');

    browser.wait(EC.visibilityOf($('#config-statusCode')), 5000);

    expect(element(by.model('action.config.statusCode')).getAttribute('value')).toEqual('200');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Action successful updated');
  });

  it('Delete action', function() {
    browser.get('#/action');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('action.name')).getAttribute('value')).toEqual('test-action');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Action successful deleted');
  });

});
