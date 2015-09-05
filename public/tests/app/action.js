
describe('Action tests', function() {

  it('List action', function() {
    browser.get('#/action');

    var routes = element.all(by.repeater('action in actions'));
    expect(routes.count()).toEqual(3);
    expect(routes.get(0).getText()).toEqual('Sql-Fetch-Row');
    expect(routes.get(1).getText()).toEqual('Sql-Fetch-All');
    expect(routes.get(2).getText()).toEqual('Welcome');
  });

});
