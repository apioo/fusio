
describe('Log tests', function() {

  it('List log', function() {
    browser.get('#/log');

    var routes = element.all(by.repeater('log in logs'));
    expect(routes.count()).toEqual(3);
    expect(routes.get(0).getText()).toMatch('POST /foo');
    expect(routes.get(1).getText()).toMatch('GET /bar');
    expect(routes.get(2).getText()).toMatch('GET /bar');
  });

});
