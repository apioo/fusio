
describe('Connection tests', function() {

  it('List connection', function() {
    browser.get('#/connection');

    var routes = element.all(by.repeater('connection in connections'));
    expect(routes.count()).toEqual(3);
    expect(routes.get(0).getText()).toEqual('MongoDB');
    expect(routes.get(1).getText()).toEqual('DBAL');
    expect(routes.get(2).getText()).toEqual('Native-Connection');
  });

  it('Create connection', function() {
    browser.get('#/connection');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('connection.name')).sendKeys('test-connection');

    var connectionOptions = element.all(by.options('conn.class as conn.name for conn in connections'));
    expect(connectionOptions.get(0).getText()).toEqual('Beanstalk');
    expect(connectionOptions.get(1).getText()).toEqual('SQL-Connection');
    expect(connectionOptions.get(2).getText()).toEqual('SQL-Connection (advanced)');
    expect(connectionOptions.get(3).getText()).toEqual('Mongo-DB');
    expect(connectionOptions.get(4).getText()).toEqual('Native');
    expect(connectionOptions.get(5).getText()).toEqual('RabbitMQ');
    connectionOptions.get(4).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Connection successful created');
  });

  it('Update connection', function() {
    browser.get('#/connection');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('connection.name')).getAttribute('value')).toEqual('test-connection');
    expect(element(by.model('connection.class')).getAttribute('value')).toEqual('Fusio\\Impl\\Connection\\Native');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Connection successful updated');
  });

  it('Delete connection', function() {
    browser.get('#/connection');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('connection.name')).getAttribute('value')).toEqual('test-connection');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Connection successful deleted');
  });

});
