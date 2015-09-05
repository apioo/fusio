
describe('Connection tests', function() {

  it('List connection', function() {
    browser.get('#/connection');

    var routes = element.all(by.repeater('connection in connections'));
    expect(routes.count()).toEqual(2);
    expect(routes.get(0).getText()).toEqual('DBAL');
    expect(routes.get(1).getText()).toEqual('Native-Connection');
  });

});
