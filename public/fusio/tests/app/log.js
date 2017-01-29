'use strict';

describe('Log tests', function() {

  it('List log', function() {
    browser.get('#/log');

    var logs = element.all(by.repeater('log in logs'));
    expect(logs.count()).toEqual(2);
    expect(logs.get(0).getText()).toMatch('GET /bar');
    expect(logs.get(1).getText()).toMatch('GET /bar');
  });

});
