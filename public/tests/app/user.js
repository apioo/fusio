
describe('User tests', function() {

  it('List user', function() {
    browser.get('#/user');

    var routes = element.all(by.repeater('user in users'));
    expect(routes.count()).toEqual(4);
    expect(routes.get(0).getText()).toMatch('Developer');
    expect(routes.get(1).getText()).toMatch('Disabled');
    expect(routes.get(2).getText()).toMatch('Consumer');
    expect(routes.get(3).getText()).toMatch('Administrator');
  });

});
