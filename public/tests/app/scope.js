
describe('Scope tests', function() {

  it('List scope', function() {
    browser.get('#/scope');

    var routes = element.all(by.repeater('scope in scopes'));
    expect(routes.count()).toEqual(4);
    expect(routes.get(0).getText()).toEqual('bar');
    expect(routes.get(1).getText()).toEqual('foo');
    expect(routes.get(2).getText()).toEqual('authorization');
    expect(routes.get(3).getText()).toEqual('backend');
  });

});
