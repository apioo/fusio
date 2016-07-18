'use strict';

describe('Statistic tests', function() {

  it('List statistic', function() {
    browser.get('#/statistic');

    var routes = element.all(by.options('statistic.value as statistic.name for statistic in statistics'));
    expect(routes.count()).toEqual(4);
    expect(routes.get(0).getText()).toEqual('Incoming requests');
    expect(routes.get(1).getText()).toEqual('Most used routes');
    expect(routes.get(2).getText()).toEqual('Most used apps');
    expect(routes.get(3).getText()).toEqual('Errors per route');

    routes.get(0).click();
    browser.waitForAngular();

    routes.get(1).click();
    browser.waitForAngular();

    routes.get(2).click();
    browser.waitForAngular();

    routes.get(3).click();
    browser.waitForAngular();
  });

});
