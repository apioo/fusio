'use strict';

describe('Config tests', function() {

  it('List config', function() {
    browser.get('#/config');

    var configs = element.all(by.repeater('config in configs'));
    expect(configs.count()).toEqual(11);
    expect(configs.get(0).getText()).toMatch('app_approval');
    expect(configs.get(1).getText()).toMatch('app_consumer');
    expect(configs.get(2).getText()).toMatch('cors_allow_origin');
    expect(configs.get(3).getText()).toMatch('mail_register_body');
    expect(configs.get(4).getText()).toMatch('mail_register_subject');
  });

  it('Update config', function() {
    browser.get('#/config');

    var EC = protractor.ExpectedConditions;

    element.all(by.css('div.fusio-options a:nth-child(1)')).get(1).click();

    browser.wait(EC.visibilityOf($('div.modal-body')), 5000);

    expect(element(by.model('config.value')).getAttribute('value')).toEqual('16');

    element.all(by.model('config.value')).click();

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Config successful updated');
  });

});
