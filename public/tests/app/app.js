
describe('App tests', function() {

  it('List app', function() {
    browser.get('#/app');

    var routes = element.all(by.repeater('app in apps'));
    expect(routes.count()).toEqual(4);
    expect(routes.get(0).getText()).toMatch('Deactivated');
    expect(routes.get(1).getText()).toMatch('Pending');
    expect(routes.get(2).getText()).toMatch('Foo-App');
    expect(routes.get(3).getText()).toMatch('Backend');
  });

});
