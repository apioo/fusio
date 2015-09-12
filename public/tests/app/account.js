
describe('Account tests', function() {

  it('Change password', function() {
    browser.get('#/account/change_password');

    var EC = protractor.ExpectedConditions;

    element(by.model('account.oldPassword')).sendKeys('3632465b3b5d1b8b4b8e56f74600da00cea92baf');
    element(by.model('account.newPassword')).sendKeys('3632465b3b5d1b8b4b8e56f74600da00cea92baf');
    element(by.model('account.verifyPassword')).sendKeys('3632465b3b5d1b8b4b8e56f74600da00cea92baf');

    $('button.btn-primary').click();

    browser.wait(EC.visibilityOf($('div.alert-success')), 5000);

    expect($('div.alert-success > div').getText()).toEqual('Password successful changed');
  });

});
