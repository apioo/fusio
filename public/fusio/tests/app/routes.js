'use strict';

var http = require('request-promise');

describe('Routes tests', function() {

  it('List routes', function() {
    browser.get('#/routes');

    var routes = element.all(by.repeater('route in routes'));
    expect(routes.count()).toEqual(2);
    expect(routes.get(0).getText()).toEqual('/foo');
    expect(routes.get(1).getText()).toEqual('/');
  });

  it('Create route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('route.path')).sendKeys('/test');
    element.all(by.model('config.active')).get(0).click();
    element.all(by.model('config.public')).get(0).click();

    var responseOptions = element.all(by.options('schema.id as schema.name for schema in schemas'));
    expect(responseOptions.get(3).getText()).toEqual('');
    expect(responseOptions.get(4).getText()).toEqual('Foo-Schema');
    expect(responseOptions.get(5).getText()).toEqual('Passthru');

    responseOptions.get(5).click();

    var actionOptions = element.all(by.options('action.id as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('');
    expect(actionOptions.get(1).getText()).toEqual('app-action');
    expect(actionOptions.get(2).getText()).toEqual('Sql-Table');
    expect(actionOptions.get(3).getText()).toEqual('Util-Static-Response');
    expect(actionOptions.get(4).getText()).toEqual('Welcome');

    actionOptions.get(1).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Route successful created');
  });

  it('Change status to development', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('status.key as status.value for status in statuuus'));
    statusOptions.get(0).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');

    $('div.alert-success > div').getText().then(function(){
      // make a request to the endpoint
      expect(http({
        method: 'GET',
        uri: browser.baseUrl.replace('/fusio/index.htm', '/index.php/test'),
        headers: {
          'User-Agent': 'Fusio-Test'
        },
        json: true,
        simple: false
      })).toEqual({
        totalResults: 2,
        itemsPerPage: 16,
        startIndex: 0,
        entry: [{
          id: '2',
          title: 'bar',
          content: 'foo',
          date: '2015-02-27 19:59:15'
        }, {
          id: '1',
          title: 'foo',
          content: 'bar',
          date: '2015-02-27 19:59:15'
        }]
      });
    });
  });

  it('Change status to production', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('status.key as status.value for status in statuuus'));
    statusOptions.get(1).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');

    $('div.alert-success > div').getText().then(function(){
      // make a request to the endpoint
      expect(http({
        method: 'GET',
        uri: browser.baseUrl.replace('/fusio/index.htm', '/index.php/test'),
        headers: {
          'User-Agent': 'Fusio-Test'
        },
        json: true,
        simple: false
      })).toEqual({
        totalResults: 2,
        itemsPerPage: 16,
        startIndex: 0,
        entry: [{
          id: '2',
          title: 'bar',
          content: 'foo',
          date: '2015-02-27 19:59:15'
        }, {
          id: '1',
          title: 'foo',
          content: 'bar',
          date: '2015-02-27 19:59:15'
        }]
      });
    });
  });

  it('Change status to deprecated', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('status.key as status.value for status in statuuus'));
    statusOptions.get(2).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');

    $('div.alert-success > div').getText().then(function(){
      // make a request to the endpoint
      expect(http({
        method: 'GET',
        uri: browser.baseUrl.replace('/fusio/index.htm', '/index.php/test'),
        headers: {
          'User-Agent': 'Fusio-Test'
        },
        json: true,
        simple: false
      })).toEqual({
        totalResults: 2,
        itemsPerPage: 16,
        startIndex: 0,
        entry: [{
          id: '2',
          title: 'bar',
          content: 'foo',
          date: '2015-02-27 19:59:15'
        }, {
          id: '1',
          title: 'foo',
          content: 'bar',
          date: '2015-02-27 19:59:15'
        }]
      });
    });
  });

  it('Change status to closed', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var statusOptions = element.all(by.options('status.key as status.value for status in statuuus'));
    statusOptions.get(3).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');

    $('div.alert-success > div').getText().then(function(){
      // make a request to the endpoint
      expect(http({
        method: 'GET',
        uri: browser.baseUrl.replace('/fusio/index.htm', '/index.php/test'),
        headers: {
          'User-Agent': 'Fusio-Test'
        },
        json: true,
        simple: false
      })).toEqual({
        success: false,
        title: 'Internal Server Error',
        message: 'Resource is not longer supported'
      });
    });
  });

  it('Update route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(3)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('route.path')).getAttribute('value')).toEqual('/test');
    expect(element.all(by.model('config.active')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('config.public')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('config.response')).get(0).getAttribute('value')).toEqual('number:1');
    expect(element.all(by.model('config.action')).get(0).getAttribute('value')).toEqual('number:5');

    var responseOptions = element.all(by.options('schema.id as schema.name for schema in schemas'));
    expect(responseOptions.get(3).getText()).toEqual('Foo-Schema');
    expect(responseOptions.get(4).getText()).toEqual('Passthru');

    var actionOptions = element.all(by.options('action.id as action.name for action in actions'));
    expect(actionOptions.get(0).getText()).toEqual('app-action');
    expect(actionOptions.get(1).getText()).toEqual('Sql-Table');
    expect(actionOptions.get(2).getText()).toEqual('Util-Static-Response');
    expect(actionOptions.get(3).getText()).toEqual('Welcome');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful updated');
  });

  it('Delete route', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(4)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('route.path')).getAttribute('value')).toEqual('/test');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Routes successful deleted');
  });

  it('List route actions', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    var actions = element.all(by.repeater('action in actions'));
    expect(actions.count()).toEqual(1);
    expect(actions.get(0).getText()).toEqual('Sql-Table');

  });

  it('List route schemas', function() {
    browser.get('#/routes');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    var schemas = element.all(by.repeater('schema in schemas'));
    expect(schemas.count()).toEqual(2);
    expect(schemas.get(0).getText()).toEqual('Foo-Schema');
    expect(schemas.get(1).getText()).toEqual('Passthru');

  });

});


