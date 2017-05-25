'use strict';

describe('Rate tests', function() {

  it('List rate', function() {
    browser.get('#/rate');

    var rates = element.all(by.repeater('rate in rates'));
    expect(rates.count()).toEqual(4);
    expect(rates.get(0).getText()).toMatch('gold');
    expect(rates.get(1).getText()).toMatch('silver');
    expect(rates.get(2).getText()).toMatch('Default-Anonymous');
    expect(rates.get(3).getText()).toMatch('Default');
  });

  it('Create rate', function() {
    browser.get('#/rate');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('rate.name')).sendKeys('test-rate');
    element(by.model('rate.priority')).clear().sendKeys(1024);
    element(by.model('rate.rateLimit')).clear().sendKeys(60);
    element(by.model('timespan.value')).clear().sendKeys(2);

    var intervalOptions = element.all(by.options('interval.key as interval.value for interval in intervals'));
    expect(intervalOptions.get(0).getText()).toEqual('minute');
    expect(intervalOptions.get(1).getText()).toEqual('hour');
    expect(intervalOptions.get(2).getText()).toEqual('day');
    expect(intervalOptions.get(3).getText()).toEqual('week');
    expect(intervalOptions.get(4).getText()).toEqual('month');
    intervalOptions.get(2).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Rate successful created');
  });

  it('Update rate', function() {
    browser.get('#/rate');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('rate.name')).getAttribute('value')).toEqual('test-rate');
    expect(element(by.model('rate.priority')).getAttribute('value')).toEqual('1024');
    expect(element(by.model('rate.rateLimit')).getAttribute('value')).toEqual('60');
    expect(element(by.model('timespan.value')).getAttribute('value')).toEqual('2');
    expect(element(by.model('timespan.unit')).getAttribute('value')).toEqual('string:day');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Rate successful updated');
  });

  it('Delete rate', function() {
    browser.get('#/rate');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(2)')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('rate.name')).getAttribute('value')).toEqual('test-rate');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Rate successful deleted');
  });

});
