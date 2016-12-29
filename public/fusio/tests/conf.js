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
    browserName: 'firefox'
  },
  onPrepare: function() {
    // login
    browser.driver.get('http://127.0.0.1:8008/fusio/index.htm#/login');

    var usernameInput = browser.driver.findElement(by.id('username'));
    var passwordInput = browser.driver.findElement(by.id('password'));
    var submitButton = browser.driver.findElement(by.css('button[type="submit"]'));

    return usernameInput.isPresent().then(function(){
      usernameInput.sendKeys('Developer');
      passwordInput.sendKeys('qf2vX10Ec3wFZHx0K1eL');
      submitButton.click();

      return browser.driver.wait(function() {
        return browser.driver.getCurrentUrl().then(function(url) {
          return /#\/dashboard/.test(url);
        });
      }, 10000);
    });
  }
};
