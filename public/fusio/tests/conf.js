'use strict';

exports.config = {
  seleniumAddress: 'http://localhost:4444/wd/hub',
  specs: [
    'app/account.js',
    'app/action.js',
    'app/app.js',
    'app/config.js',
    'app/connection.js',
    'app/log.js',
    'app/rate.js',
    'app/routes.js',
    'app/schema.js',
    'app/scope.js',
    'app/statistic.js',
    'app/user.js',
    'app/import.js'
  ],
  baseUrl: 'http://127.0.0.1:8008/fusio/index.htm',
  capabilities: {
    browserName: 'chrome'
  },
  onPrepare: function() {
    // login
    var EC = protractor.ExpectedConditions;

    browser.driver.get('http://127.0.0.1:8008/fusio/index.htm');

    browser.wait(EC.visibilityOf(element(by.id('loginUsername'))), 5000);

    element(by.id('loginUsername')).sendKeys('Developer');
    element(by.id('loginPassword')).sendKeys('qf2vX10Ec3wFZHx0K1eL');

    browser.wait(EC.elementToBeClickable(element(by.id('loginButton'))), 5000);

    element(by.id('loginButton')).click();

    return browser.driver.wait(function() {
      return browser.driver.getCurrentUrl().then(function(url) {
        return /#\/dashboard/.test(url);
      });
    }, 10000);
  },
  plugins: [{
    package: 'protractor-console-plugin',
    failOnWarning: false,
    failOnError: true,
    logWarnings: true
  }]
};
