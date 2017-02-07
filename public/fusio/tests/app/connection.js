'use strict';

describe('Connection tests', function() {

  it('List connection', function() {
    browser.get('#/connection');

    var routes = element.all(by.repeater('connection in connections'));
    expect(routes.count()).toEqual(1);
    expect(routes.get(0).getText()).toEqual('Native');
  });

  it('Create connection', function() {
    browser.get('#/connection');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('connection.name')).sendKeys('test-connection');

    var connectionOptions = element.all(by.options('conn.class as conn.name for conn in connections'));
    expect(connectionOptions.get(0).getText()).toEqual('HTTP');
    expect(connectionOptions.get(1).getText()).toEqual('SQL');
    expect(connectionOptions.get(2).getText()).toEqual('SQL (advanced)');
    connectionOptions.get(2).click();

    browser.wait(EC.visibilityOf($('#config-url')), 5000);

    element(by.css('#config-url')).sendKeys('sqlite:///:memory:');

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
    expect(element(by.model('connection.class')).getAttribute('value')).toEqual('Fusio\\Adapter\\Sql\\Connection\\SqlAdvanced');

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

  it('Create connection routes', function() {
    browser.get('#/connection');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    var configUrl;
    if (process.env.DB && process.env.DB == 'mysql') {
      configUrl = 'mysql://root@localhost/fusio_ui';
    } else {
      configUrl = 'sqlite:///../cache/fusio_ui.db';
    }

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('connection.name')).sendKeys('app-connection');

    var connectionOptions = element.all(by.options('conn.class as conn.name for conn in connections'));
    connectionOptions.get(2).click();

    browser.wait(EC.visibilityOf($('#config-url')), 5000);

    element(by.css('#config-url')).sendKeys(configUrl);

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Connection successful created');
  });

});
