
describe('Action tests', function() {

  it('List action', function() {
    browser.get('#/action');

    var routes = element.all(by.repeater('action in actions'));
    expect(routes.count()).toEqual(3);
    expect(routes.get(0).getText()).toEqual('Sql-Fetch-Row');
    expect(routes.get(1).getText()).toEqual('Sql-Fetch-All');
    expect(routes.get(2).getText()).toEqual('Welcome');
  });

  it('Create action', function() {
    browser.get('#/action');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('action.name')).sendKeys('test-action');

    var actionOptions = element.all(by.options('action.class as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('Beanstalk-Push');
    expect(actionOptions.get(1).getText()).toEqual('Cache-Response');
    expect(actionOptions.get(2).getText()).toEqual('Composite');
    expect(actionOptions.get(3).getText()).toEqual('Condition');
    expect(actionOptions.get(4).getText()).toEqual('HTTP-Request');
    expect(actionOptions.get(5).getText()).toEqual('Pipe');
    expect(actionOptions.get(6).getText()).toEqual('RabbitMQ-Push');
    expect(actionOptions.get(7).getText()).toEqual('SQL-Execute');
    expect(actionOptions.get(8).getText()).toEqual('SQL-Fetch-All');
    expect(actionOptions.get(9).getText()).toEqual('SQL-Fetch-Row');
    expect(actionOptions.get(10).getText()).toEqual('Static-Response');

    actionOptions.get(10).click();

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
    expect(element(by.model('action.class')).getAttribute('value')).toEqual('Fusio\\Impl\\Action\\StaticResponse');

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
