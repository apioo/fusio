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
  baseUrl: 'http://127.0.0.1:8008/fusio',
  capabilities: {
    browserName: 'firefox'
  },
  onPrepare: function() {
    // login
    browser.driver.get('http://127.0.0.1:8008/fusio');
    browser.driver.findElement(by.id('username')).sendKeys('Developer');
    browser.driver.findElement(by.id('password')).sendKeys('qf2vX10Ec3wFZHx0K1eL');
    browser.driver.findElement(by.css('button[type="submit"]')).click();

    return browser.driver.wait(function() {
      return browser.driver.getCurrentUrl().then(function(url) {
        return /#\/dashboard/.test(url);
      });
    }, 10000);
  }
};
