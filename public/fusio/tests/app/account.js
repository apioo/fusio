'use strict';

describe('Account tests', function() {

  it('Change password', function() {
    browser.get('#/account/change_password');

    var EC = protractor.ExpectedConditions;

    element(by.model('account.oldPassword')).sendKeys('qf2vX10Ec3wFZHx0K1eL');
    element(by.model('account.newPassword')).sendKeys('qf2vX10Ec3wFZHx0K1eL!');
    element(by.model('account.verifyPassword')).sendKeys('qf2vX10Ec3wFZHx0K1eL!');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Password successful changed');
  });

});
