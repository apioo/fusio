
describe('Database tests', function() {

  it('Create table', function() {
    browser.get('#/database');

    var EC = protractor.ExpectedConditions;

    $('a.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    element(by.model('table.name')).sendKeys('foobar');

    element.all(by.css('button.btn-default')).first().click();
    element.all(by.css('button.btn-default')).first().click();
    element.all(by.css('button.btn-default')).first().click();

    var columns = element.all(by.repeater('column in table.columns'));
    columns.get(0).element(by.model('column.name')).clear().sendKeys('id');
    columns.get(0).element(by.cssContainingText('option', 'integer')).click();
    columns.get(1).element(by.model('column.name')).clear().sendKeys('name');
    columns.get(1).element(by.cssContainingText('option', 'string')).click();
    columns.get(2).element(by.model('column.name')).clear().sendKeys('content');
    columns.get(2).element(by.cssContainingText('option', 'text')).click();
    columns.get(3).element(by.model('column.name')).clear().sendKeys('date');
    columns.get(3).element(by.cssContainingText('option', 'datetime')).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Table successful created');
  });

  it('List table', function() {
    browser.get('#/database');

    var tables = element.all(by.repeater('table in tables'));
    expect(tables.count()).toEqual(2);
    expect(tables.get(0).getText()).toEqual('app_news');
    expect(tables.get(1).getText()).toEqual('foobar');
  });

  it('Table update', function() {
    browser.get('#/database');

    var EC = protractor.ExpectedConditions;

    var tables = element.all(by.repeater('table in tables'));
    tables.get(1).click();

    element.all(by.css('a.btn-default')).get(1).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('table.name')).getAttribute('value')).toEqual('foobar');

    var columns = element.all(by.model('column.name'));
    expect(columns.count()).toEqual(4);
    expect(columns.get(0).getAttribute('value')).toEqual('id');
    expect(columns.get(1).getAttribute('value')).toEqual('name');
    expect(columns.get(2).getAttribute('value')).toEqual('content');
    expect(columns.get(3).getAttribute('value')).toEqual('date');

    element.all(by.css('.fusio-options a.btn-default')).get(2).click();

    $('button.btn-primary').click();

    var queries = element.all(by.repeater('query in response.queries'));
    expect(queries.count()).toEqual(1);
    expect(queries.get(0).getText()).toMatch(/^ALTER TABLE foobar DROP content|CREATE TEMPORARY TABLE __temp__app_news AS SELECT id, title, date FROM app_news$/);

    $('button.btn-danger').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Table successful updated');
      
  });

  it('Table delete', function() {
    browser.get('#/database');

    var EC = protractor.ExpectedConditions;

    var tables = element.all(by.repeater('table in tables'));
    tables.get(1).click();

    element.all(by.css('a.btn-danger')).first().click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('table.name')).getAttribute('value')).toEqual('foobar');

    $('button.btn-primary').click();

    var queries = element.all(by.repeater('query in response.queries'));
    expect(queries.count()).toEqual(1);
    expect(queries.get(0).getText()).toEqual('DROP TABLE foobar');

    $('button.btn-danger').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Table successful deleted');
  });

});
