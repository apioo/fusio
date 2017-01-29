'use strict';

describe('Error tests', function() {

  it('List error', function() {
    browser.get('#/error');

    var routes = element.all(by.repeater('error in errors'));
    expect(routes.count()).toEqual(1);
    expect(routes.get(0).getText()).toMatch('GET /bar');
  });

});
