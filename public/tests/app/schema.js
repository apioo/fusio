
describe('Schema tests', function() {

  it('List schema', function() {
    browser.get('#/schema');

    var routes = element.all(by.repeater('schema in schemas'));
    expect(routes.count()).toEqual(2);
    expect(routes.get(0).getText()).toEqual('Foo-Schema');
    expect(routes.get(1).getText()).toEqual('Passthru');
  });

});
