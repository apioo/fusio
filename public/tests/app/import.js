
describe('Import tests', function() {

  it('List import', function() {
    browser.get('#/import');

    var EC = protractor.ExpectedConditions;

    var raml = '#%RAML 0.8' + protractor.Key.ENTER;
    raml+= 'title: Example API' + protractor.Key.ENTER;
    raml+= 'baseUri: /bar' + protractor.Key.ENTER;
    raml+= '/foo:' + protractor.Key.ENTER;
    raml+= '  get:' + protractor.Key.ENTER;
    raml+= '    responses:' + protractor.Key.ENTER;
    raml+= '      200:' + protractor.Key.ENTER;
    raml+= '        body:' + protractor.Key.ENTER;
    raml+= '          application/json:' + protractor.Key.ENTER;
    raml+= '            schema: |' + protractor.Key.ENTER;
    raml+= '              {' + protractor.Key.ENTER;
    raml+= '                "type": "object",' + protractor.Key.ENTER;
    raml+= '                "properties": {' + protractor.Key.ENTER;
    raml+= '                  "success": {' + protractor.Key.ENTER;
    raml+= '                    "type": "boolean"' + protractor.Key.ENTER;
    raml+= '                  },' + protractor.Key.ENTER;
    raml+= '                  "message": {' + protractor.Key.ENTER;
    raml+= '                    "type": "string"' + protractor.Key.ENTER;
    raml+= '                  }' + protractor.Key.ENTER;
    raml+= '                }' + protractor.Key.ENTER;
    raml+= '              }' + protractor.Key.ENTER;
    raml+= '  post:' + protractor.Key.ENTER;
    raml+= '    body:' + protractor.Key.ENTER;
    raml+= '      application/json:' + protractor.Key.ENTER;
    raml+= '        schema: |' + protractor.Key.ENTER;
    raml+= '          {' + protractor.Key.ENTER;
    raml+= '            "type": "object",' + protractor.Key.ENTER;
    raml+= '            "properties": {' + protractor.Key.ENTER;
    raml+= '              "success": {' + protractor.Key.ENTER;
    raml+= '                "type": "boolean"' + protractor.Key.ENTER;
    raml+= '              },' + protractor.Key.ENTER;
    raml+= '              "message": {' + protractor.Key.ENTER;
    raml+= '                "type": "string"' + protractor.Key.ENTER;
    raml+= '              }' + protractor.Key.ENTER;
    raml+= '            }' + protractor.Key.ENTER;
    raml+= '          }' + protractor.Key.ENTER;
    raml+= '' + protractor.Key.ENTER;

    element(by.css('textarea.ace_text-input')).sendKeys(raml);

    $('a.btn-default').click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    var routes = element.all(by.repeater('route in data.routes'));
    expect(routes.count()).toEqual(1);
    expect(routes.get(0).getText()).toEqual('/bar/foo');

    var schemas = element.all(by.repeater('schem in data.schema'));
    expect(schemas.count()).toEqual(2);
    expect(schemas.get(0).getText()).toEqual('bar-foo-GET-response');
    expect(schemas.get(1).getText()).toEqual('bar-foo-POST-request');

    // click route details
    routes.get(0).$('a').click();

    var modal = $('div.modal-body').all().get(1);

    browser.wait(EC.visibilityOf(modal), 5000);

    expect(element(by.model('route.path')).getAttribute('value')).toEqual('/bar/foo');

    expect(element.all(by.model('method.active')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.public')).get(0).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.response')).get(0).getAttribute('value')).toEqual('${schema.bar-foo-GET-response}');
    expect(element.all(by.model('method.action')).get(0).getAttribute('value')).toEqual('${action.Welcome}');

    var tabs = element.all(by.repeater('method in version.methods'));
    tabs.get(1).click();

    expect(element.all(by.model('method.active')).get(1).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.public')).get(1).getAttribute('value')).toEqual('on');
    expect(element.all(by.model('method.request')).get(1).getAttribute('value')).toEqual('${schema.bar-foo-POST-request}');
    expect(element.all(by.model('method.response')).get(1).getAttribute('value')).toEqual('${schema.Passthru}');
    expect(element.all(by.model('method.action')).get(1).getAttribute('value')).toEqual('${action.Welcome}');

    // close
    modal.$('button').click();

    // click schema details
    schemas.get(0).$('a').click();

    var modal = $('div.modal-body').all().get(1);

    browser.wait(EC.visibilityOf(modal), 5000);

    expect(element(by.model('schema.name')).getAttribute('value')).toEqual('bar-foo-GET-response');

    // close
    modal.$('button').click();

    // import data
    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('You have successfully imported the provided data');

  });

});
