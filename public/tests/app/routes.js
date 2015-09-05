
describe('Routes tests', function() {

  it('List routes', function() {
    browser.get('#/routes');

    var routes = element.all(by.repeater('route in routes'));
    expect(routes.count()).toEqual(2);
    expect(routes.get(0).getText()).toEqual('GET|POST|PUT|DELETE /foo');
    expect(routes.get(1).getText()).toEqual('GET|POST|PUT|DELETE /');
  });

  it('Create route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('route.path')).sendKeys('/test');
    element.all(by.model('method.active')).get(0).click();
    element.all(by.model('method.public')).get(0).click();

    var responseOptions = element.all(by.options('schema.id as schema.name for schema in schemas'));
    expect(responseOptions.get(3).getText()).toEqual('');
    expect(responseOptions.get(4).getText()).toEqual('Foo-Schema');
    expect(responseOptions.get(5).getText()).toEqual('Passthru');

    responseOptions.get(5).click();

    var actionOptions = element.all(by.options('action.id as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('');
    expect(actionOptions.get(1).getText()).toEqual('Sql-Fetch-Row');
    expect(actionOptions.get(2).getText()).toEqual('Sql-Fetch-All');
    expect(actionOptions.get(3).getText()).toEqual('Welcome');

    actionOptions.get(1).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Route successful created');
  });

  it('Update route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    $('div.fusio-options a:nth-child(1)').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('route.path')).getAttribute('value')).toEqual('/test');
    expect(element.all(by.model('method.active')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.public')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.response')).get(0).getAttribute('value')).toEqual('1');
    expect(element.all(by.model('method.action')).get(0).getAttribute('value')).toEqual('0');

    var responseOptions = element.all(by.options('schema.id as schema.name for schema in schemas'));
    expect(responseOptions.get(3).getText()).toEqual('Foo-Schema');
    expect(responseOptions.get(4).getText()).toEqual('Passthru');

    var actionOptions = element.all(by.options('action.id as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('Sql-Fetch-Row');
    expect(actionOptions.get(1).getText()).toEqual('Sql-Fetch-All');
    expect(actionOptions.get(2).getText()).toEqual('Welcome');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');
  });

  it('Delete route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    $('div.fusio-options a:nth-child(2)').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('route.path')).getAttribute('value')).toEqual('/test');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful deleted');
  });

});


